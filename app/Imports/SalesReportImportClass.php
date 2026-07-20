<?php

namespace App\Imports;

use App\Models\Catalog\Producto;
use App\Models\Inventory\InventarioSede;
use App\Models\Inventory\KardexMovimiento;
use App\Models\Inventory\SalesReportImport;
use App\Models\Inventory\SalesReportImportItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SalesReportImportClass implements ToCollection
{
    protected int $sedeId;
    protected string $fileName;
    protected array $loadedProducts = [];
    protected array $notFoundProducts = [];
    protected array $alertProducts = [];

    public function __construct(int $sedeId, string $fileName)
    {
        $this->sedeId = $sedeId;
        $this->fileName = $fileName;
    }

    /**
     * Procesar la colección de filas del Excel.
     */
    public function collection(Collection $collection)
    {
        // 1. Obtener el rango de fechas en la celda E3 (fila 3 = índice 2, col E = índice 4)
        $dateRange = null;
        if (isset($collection[2][4])) {
            $dateRange = trim($collection[2][4]);
        }

        if (!$dateRange || empty($dateRange)) {
            throw new \Exception("No se encontró el rango de fechas en la celda E3 (Fila 3, Columna E).");
        }

        // 2. Buscar o crear el registro principal del reporte
        $import = SalesReportImport::firstOrCreate(
            [
                'date_range' => $dateRange,
                'sede_id'    => $this->sedeId,
            ],
            [
                'file_name'  => $this->fileName,
            ]
        );

        // 3. Procesar las filas de datos. Los encabezados están en la fila 10, los datos comienzan en la fila 11 (índice 10)
        foreach ($collection->slice(10) as $row) {
            // Verificar si el nombre del producto en Columna C (índice 2) está vacío
            if (!isset($row[2]) || empty(trim($row[2]))) {
                continue;
            }

            $productoNombre = trim($row[2]);

            // Leer y sanitizar cada una de las columnas
            $puntoOperacion = isset($row[0]) ? trim($row[0]) : null;
            $grupo          = isset($row[1]) ? trim($row[1]) : null;
            $unidad         = isset($row[3]) ? trim($row[3]) : null;

            $cortesia      = $this->parseNumeric($row[4] ?? 0);
            $horaFeliz     = $this->parseNumeric($row[5] ?? 0);
            $consumo       = $this->parseNumeric($row[6] ?? 0);
            $bajaDano      = $this->parseNumeric($row[7] ?? 0);
            $cantidadVenta = $this->parseNumeric($row[8] ?? 0);
            $ventaBruta    = $this->parseNumeric($row[9] ?? 0);
            $descuento     = $this->parseNumeric($row[10] ?? 0);
            $ventaNeta     = $this->parseNumeric($row[11] ?? 0);
            $impuesto      = $this->parseNumeric($row[12] ?? 0);
            $total         = $this->parseNumeric($row[13] ?? 0);
            $vUnitario     = $this->parseNumeric($row[14] ?? 0);
            $porcentaje    = $this->parseNumeric($row[15] ?? 0);

            $itemData = [
                'punto_operacion' => $puntoOperacion,
                'grupo'           => $grupo,
                'producto_nombre' => $productoNombre,
                'unidad'          => $unidad,
                'cortesia'        => $cortesia,
                'hora_feliz'      => $horaFeliz,
                'consumo'         => $consumo,
                'baja_dano'       => $bajaDano,
                'cantidad_venta'  => $cantidadVenta,
                'venta_bruta'     => $ventaBruta,
                'descuento'       => $descuento,
                'venta_neta'      => $ventaNeta,
                'impuesto'        => $impuesto,
                'total'           => $total,
                'v_unitario'      => $vUnitario,
                'porcentaje'      => $porcentaje,
            ];

            // Buscar producto por nombre (cruce exacto en catálogo)
            $producto = Producto::where('nombre', $productoNombre)->first();

            // Buscar si tiene registro previo de stock en el inventario de esta sede específica
            $inventario = null;
            if ($producto) {
                $inventario = InventarioSede::where('sede_id', $this->sedeId)
                    ->where('producto_id', $producto->id)
                    ->first();
            }

            // Si el producto no existe o NO tiene registro previo en el inventario de esa sede, se guarda el histórico como no cargado (product_id = null)
            if (!$producto || !$inventario) {
                $itemData['import_id']  = $import->id;
                $itemData['product_id'] = null;
                SalesReportImportItem::create($itemData);

                $this->notFoundProducts[] = $productoNombre;
                continue;
            }

            // Registrar que este producto sí se cargó/actualizó
            $this->loadedProducts[] = $productoNombre;

            // Buscar si ya existe este ítem importado previamente
            $existingItem = SalesReportImportItem::where('import_id', $import->id)
                ->where('producto_nombre', $productoNombre)
                ->first();

            if ($existingItem) {
                $cantidadHistorica = (float) $existingItem->cantidad_venta;

                if ($cantidadVenta > $cantidadHistorica) {
                    // Cantidad nueva mayor: calcular diferencia y descontar la diferencia
                    $diferencia = $cantidadVenta - $cantidadHistorica;

                    $saldoAnterior = (float) $inventario->cantidad_actual;
                    $saldoNuevo = $saldoAnterior - $diferencia;
                    $inventario->cantidad_actual = $saldoNuevo;
                    $inventario->save();

                    // Registrar en Kardex el diferencial
                    KardexMovimiento::registrarSalida(
                        $this->sedeId,
                        $producto->id,
                        $diferencia,
                        $saldoAnterior,
                        $saldoNuevo,
                        $import,
                        (float) $producto->precio_compra,
                        "Ajuste salida venta adicional ($cantidadHistorica -> $cantidadVenta) rango: {$import->date_range}"
                    );

                    // Actualizar el histórico con los nuevos valores
                    $existingItem->update($itemData);

                } elseif ($cantidadVenta < $cantidadHistorica) {
                    // Cantidad nueva menor: no descontar ni actualizar histórico, agregar alerta
                    $this->alertProducts[] = "{$productoNombre} (Venta actual: {$cantidadVenta}, Histórico guardado: {$cantidadHistorica})";
                } else {
                    // Cantidad igual: no descontar inventario, actualizar campos financieros del histórico
                    $existingItem->update($itemData);
                }
            } else {
                // Producto nuevo en este reporte: descontar cantidad completa
                $saldoAnterior = (float) $inventario->cantidad_actual;
                $saldoNuevo = $saldoAnterior - $cantidadVenta;
                $inventario->cantidad_actual = $saldoNuevo;
                $inventario->save();

                // Registrar en Kardex salida completa
                KardexMovimiento::registrarSalida(
                    $this->sedeId,
                    $producto->id,
                    $cantidadVenta,
                    $saldoAnterior,
                    $saldoNuevo,
                    $import,
                    (float) $producto->precio_compra,
                    "Salida por venta rango: {$import->date_range}"
                );

                // Crear registro histórico completo
                $itemData['import_id']  = $import->id;
                $itemData['product_id'] = $producto->id;
                SalesReportImportItem::create($itemData);
            }
        }
    }

    /**
     * Limpiar y obtener valores numéricos seguros de las celdas del Excel.
     */
    private function parseNumeric($val): float
    {
        if (is_numeric($val)) {
            return (float) $val;
        }
        if (is_string($val)) {
            $val = trim($val);
            if ($val === '') {
                return 0.0;
            }
            $val = str_replace(['$', ' '], '', $val);
            if (strpos($val, '.') !== false && strpos($val, ',') !== false) {
                if (strpos($val, '.') < strpos($val, ',')) {
                    $val = str_replace('.', '', $val);
                    $val = str_replace(',', '.', $val);
                } else {
                    $val = str_replace(',', '', $val);
                }
            } elseif (strpos($val, ',') !== false) {
                $val = str_replace(',', '.', $val);
            }
            return is_numeric($val) ? (float) $val : 0.0;
        }
        return 0.0;
    }

    /**
     * Obtener listado de productos cargados correctamente.
     */
    public function getLoadedProducts(): array
    {
        return $this->loadedProducts;
    }

    /**
     * Obtener listado de productos que no estaban en el inventario de la sede.
     */
    public function getNotFoundProducts(): array
    {
        return $this->notFoundProducts;
    }

    /**
     * Obtener alertas de productos con cantidades reportadas menores a las históricas.
     */
    public function getAlertProducts(): array
    {
        return $this->alertProducts;
    }
}
