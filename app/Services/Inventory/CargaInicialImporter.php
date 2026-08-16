<?php

namespace App\Services\Inventory;

use App\Models\Catalog\Producto;
use App\Models\Catalog\Sede;
use App\Models\Inventory\InventarioSede;
use App\Models\Inventory\KardexMovimiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CargaInicialImporter
{
    private array $stats = [
        'productos_procesados' => 0,
        'stock_actualizado' => 0,
        'kardex_registrados' => 0,
        'saltados' => 0,
        'actualizados' => 0,
        'errores' => [],
        'lineas_procesadas' => 0,
        'imported_rows' => 0,
        'modo' => 'carga_inicial',
        'estado_inicial' => 'vacio',
    ];

    private array $cache = [];
    private int $sedeId = 1;

    /**
     * Método estático para compatibilidad
     */
    public static function import(string $filePath, int $sedeId): array
    {
        $instance = new self();
        return $instance->procesarImportacion($filePath, $sedeId);
    }

    /**
     * Método principal que valida y procesa
     */
    public function procesarImportacion(string $filePath, int $sedeId): array
    {
        // Evitar límites de tiempo de ejecución
        @set_time_limit(0);
        @ini_set('max_execution_time', 0);
        @ini_set('memory_limit', '1024M');

        Log::info('🚀 INICIO DE IMPORTACIÓN DE INVENTARIO', [
            'archivo' => basename($filePath),
            'sede_id' => $sedeId,
            'fecha' => now()->toDateTimeString(),
        ]);

        // Compatibilidad con PgBouncer en Supabase
        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo()->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
        } catch (\Exception $e) {
            Log::warning('No se pudo configurar PDO para emular prepares: ' . $e->getMessage());
        }

        try {
            Log::info('📄 Validando archivo...');
            $this->validarArchivo($filePath);
            Log::info('✅ Archivo válido');

            Log::info('🏢 Validando sede...');
            $sede = $this->validarSede($sedeId);
            Log::info("✅ Sede válida: {$sede->nombre} (ID: {$sede->id})");

            Log::info('🔍 Validando estado del sistema...');
            $estado = $this->validarEstadoSistema($sedeId);
            Log::info('📊 Estado del sistema', [
                'tiene_datos' => $estado['tiene_datos'] ? 'CON DATOS' : 'VACÍO',
                'productos_en_stock' => $estado['total_productos'],
                'movimientos_kardex' => $estado['total_movimientos'],
                'ultimo_movimiento' => $estado['ultimo_movimiento'] ?? 'Ninguno',
                'tiene_carga_inicial' => $estado['tiene_carga_inicial'] ? 'SÍ' : 'NO',
            ]);

            // Determinar modo
            $modo = $this->determinarModo($estado);
            $this->stats['modo'] = $modo;
            $this->stats['estado_inicial'] = $estado['tiene_datos'] ? 'con_datos' : 'vacio';
            
            $descripcionModo = [
                'carga_inicial' => 'CARGA INICIAL (procesa todo)',
                'incremental' => 'INCREMENTAL (solo productos nuevos)',
                'actualizar' => 'ACTUALIZAR (reemplaza cantidades existentes)',
                'completo' => 'COMPLETO (sobrescribe todo)'
            ];
            Log::info("🎯 Modo seleccionado: " . ($descripcionModo[$modo] ?? $modo));

            Log::info('📖 Leyendo archivo CSV...');
            $data = $this->parsearCSV($filePath);
            
            if (empty($data['rows'])) {
                throw new Exception("No se encontraron datos para importar.");
            }
            
            $totalFilas = count($data['rows']);
            Log::info("📊 Total de filas a procesar: {$totalFilas}");

            Log::info('💾 Cargando caché de productos...');
            $this->cargarCache($sedeId);

            Log::info('🔄 Iniciando procesamiento de productos...');
            $total = count($data['rows']);
            $procesados = 0;
            
            foreach ($data['rows'] as $item) {
                $procesados++;
                $porcentaje = round(($procesados / $total) * 100, 2);
                
                try {
                    DB::transaction(function () use ($item, $sede, $modo) {
                        $this->procesarFila($item, $sede, $modo);
                    });
                    
                    if ($procesados % 10 == 0 || $procesados == $total) {
                        Log::info("📊 Progreso: {$procesados}/{$total} ({$porcentaje}%)");
                    }
                    
                } catch (Exception $e) {
                    Log::error("❌ Error en fila {$procesados}, continuando con la siguiente...");
                    continue;
                }
            }

            $this->stats['imported_rows'] = $this->stats['productos_procesados'];

            $message = 'Importación completada exitosamente';
            if (!empty($this->stats['errores'])) {
                $message .= ' con ' . count($this->stats['errores']) . ' errores.';
            }

            $this->guardarResumenLog();

            Log::info('✅ IMPORTACIÓN FINALIZADA', [
                'productos_procesados' => $this->stats['productos_procesados'],
                'stock_actualizado' => $this->stats['stock_actualizado'],
                'kardex_registrados' => $this->stats['kardex_registrados'],
                'actualizados' => $this->stats['actualizados'],
                'errores' => count($this->stats['errores']),
            ]);

            return [
                'success' => true,
                'message' => $message,
                'imported_rows' => $this->stats['productos_procesados'],
                'modo' => $this->stats['modo'],
                'estado_inicial' => $this->stats['estado_inicial'],
                'stats' => $this->stats,
                'errores' => $this->stats['errores'],
            ];

        } catch (Exception $e) {
            Log::error('❌ ERROR CRÍTICO en importación de inventario: ' . $e->getMessage(), [
                'file' => $filePath,
                'sede_id' => $sedeId,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'imported_rows' => 0,
                'modo' => $this->stats['modo'] ?? 'desconocido',
                'stats' => $this->stats,
                'errores' => $this->stats['errores'],
            ];
        }
    }

    /**
     * DETERMINAR MODO DE OPERACIÓN
     */
    private function determinarModo(array $estado): string
    {
        // Si no hay datos → CARGA INICIAL
        if (!$estado['tiene_datos']) {
            return 'carga_inicial';
        }
        
        // Si hay datos pero NO hay carga inicial registrada → CARGA INICIAL
        if (!$estado['tiene_carga_inicial']) {
            return 'carga_inicial';
        }
        
        // Si hay datos Y hay carga inicial → ACTUALIZAR (reemplaza cantidades)
        return 'actualizar';
    }

    private function guardarResumenLog(): void
    {
        $resumen = [
            'modo' => $this->stats['modo'],
            'estado_inicial' => $this->stats['estado_inicial'],
            'productos_procesados' => $this->stats['productos_procesados'],
            'stock_actualizado' => $this->stats['stock_actualizado'],
            'kardex_registrados' => $this->stats['kardex_registrados'],
            'saltados' => $this->stats['saltados'],
            'actualizados' => $this->stats['actualizados'],
            'total_errores' => count($this->stats['errores']),
            'lineas_procesadas' => $this->stats['lineas_procesadas'],
            'imported_rows' => $this->stats['imported_rows'],
        ];
        
        if (!empty($this->stats['errores'])) {
            $resumen['primeros_errores'] = array_slice($this->stats['errores'], 0, 5);
            Log::warning('⚠️ RESUMEN DE IMPORTACIÓN CON ERRORES', $resumen);
        } else {
            Log::info('✅ RESUMEN DE IMPORTACIÓN EXITOSA', $resumen);
        }
    }

    /**
     * VALIDAR ESTADO DEL SISTEMA
     */
    private function validarEstadoSistema(int $sedeId): array
    {
        $totalProductos = InventarioSede::where('sede_id', $sedeId)->count();
        $totalMovimientos = KardexMovimiento::where('sede_id', $sedeId)->count();
        
        $ultimoMovimiento = KardexMovimiento::where('sede_id', $sedeId)
            ->orderBy('created_at', 'desc')
            ->first();
        
        $cargaInicial = KardexMovimiento::where('sede_id', $sedeId)
            ->where('notas', 'LIKE', '%Carga Inicial%')
            ->exists();

        return [
            'tiene_datos' => $totalProductos > 0 || $totalMovimientos > 0,
            'total_productos' => $totalProductos,
            'total_movimientos' => $totalMovimientos,
            'ultimo_movimiento' => $ultimoMovimiento ? $ultimoMovimiento->created_at->format('Y-m-d H:i:s') : null,
            'tiene_carga_inicial' => $cargaInicial,
        ];
    }

    /**
     * PROCESAR FILA
     */
    private function procesarFila(array $item, Sede $sede, string $modo): void
    {
        $linea = $item['linea'];
        $datos = $item['datos'];

        try {
            $producto = $this->buscarProducto($datos, $linea);
            
            if (!$producto) {
                throw new Exception("Producto no encontrado: ID={$datos['producto_id']}, Código={$datos['codigo']}");
            }

            $stockExistente = in_array($producto->id, $this->cache['stock_existente']);
            $minimo = isset($datos['stock_minimo']) ? floatval(str_replace(',', '.', $datos['stock_minimo'])) : 0;
            $maximo = isset($datos['stock_maximo']) ? floatval(str_replace(',', '.', $datos['stock_maximo'])) : 0;

            // 🔥 DECISIÓN SEGÚN MODO
            if ($modo === 'carga_inicial' || $modo === 'completo') {
                $this->procesarProducto($producto, $sede, $minimo, $maximo, $datos, $linea);
                
            } elseif ($modo === 'incremental') {
                if ($stockExistente) {
                    $this->stats['saltados']++;
                    Log::info("⏭️ Producto ya existe, saltando: {$producto->nombre} (ID: {$producto->id})");
                    return;
                }
                $this->procesarProducto($producto, $sede, $minimo, $maximo, $datos, $linea);
                
            } elseif ($modo === 'actualizar') {
                // 🔥 ACTUALIZAR: reemplaza la cantidad con la del CSV
                if ($stockExistente) {
                    Log::info("🔄 Reemplazando cantidad de: {$producto->nombre} (ID: {$producto->id})");
                    $this->stats['actualizados']++;
                } else {
                    Log::info("🆕 Creando producto nuevo: {$producto->nombre} (ID: {$producto->id})");
                }
                $this->procesarProducto($producto, $sede, $minimo, $maximo, $datos, $linea);
            }

            $this->stats['productos_procesados']++;

        } catch (Exception $e) {
            Log::error('❌ Error procesando fila ' . $linea, [
                'linea' => $linea,
                'mensaje' => $e->getMessage(),
                'datos' => $datos,
            ]);

            $this->stats['errores'][] = [
                'linea' => $linea,
                'mensaje' => $e->getMessage(),
                'datos' => $datos,
            ];
            throw $e;
        }
        
        $this->stats['lineas_procesadas']++;
    }

    /**
     * Procesar un producto individual
     */
    private function procesarProducto(Producto $producto, Sede $sede, float $minimo, float $maximo, array $datos, int $linea): void
    {
        // 🔥 La cantidad del CSV es la que queda (reemplaza, no suma)
        $cantidad = $this->validarCantidad($datos['cantidad_inicial'] ?? 0, $linea, $producto);
        
        $inventarioSede = $this->cache['stocks'][$producto->id] ?? null;

        $saldoAnterior = $inventarioSede ? floatval($inventarioSede->cantidad_actual) : 0;

        if (!$inventarioSede) {
            Log::debug("   🆕 Creando nuevo registro de stock para: {$producto->nombre}");
            $inventarioSede = new InventarioSede();
            $inventarioSede->sede_id = $sede->id;
            $inventarioSede->producto_id = $producto->id;
            $inventarioSede->created_by = auth()->id() ?? 1;
            $this->cache['stocks'][$producto->id] = $inventarioSede;
        } elseif ($inventarioSede->trashed()) {
            Log::debug("   🔄 Restaurando stock eliminado para: {$producto->nombre}");
            $inventarioSede->restore();
            $saldoAnterior = 0.0;
        } else {
            Log::debug("   🔄 Reemplazando stock para: {$producto->nombre} ({$saldoAnterior} → {$cantidad})");
        }

        // 🔥 SE ASIGNA LA CANTIDAD DEL CSV (reemplazo, no suma)
        $inventarioSede->cantidad_actual = $cantidad;
        $inventarioSede->stock_minimo = $minimo;
        $inventarioSede->stock_maximo = $maximo;
        $inventarioSede->updated_by = auth()->id() ?? 1;
        $inventarioSede->save();

        $this->stats['stock_actualizado']++;

        // 🔥 Registrar en Kardex si hay diferencia (entrada o salida)
        $hasKardex = in_array($producto->id, $this->cache['has_kardex']);

        if ($cantidad != $saldoAnterior || (!$hasKardex && $cantidad > 0)) {
            // Si no tiene Kardex, forzar saldo anterior a 0
            $forzarSaldoAnterior = !$hasKardex ? 0.0 : $saldoAnterior;
            
            // 🔥 Determinar tipo de movimiento: entrada o salida
            $tipoMovimiento = $cantidad > $saldoAnterior ? 'entrada' : 'salida';
            
            $this->registrarKardex(
                $producto, 
                $sede, 
                $inventarioSede,
                $forzarSaldoAnterior, 
                $cantidad,
                $datos,
                $tipoMovimiento  // 🔥 Pasamos el tipo de movimiento
            );

            if (!$hasKardex) {
                $this->cache['has_kardex'][] = $producto->id;
            }
        }
    }

    /**
     * Registrar movimiento en Kardex
     */
    private function registrarKardex(
        Producto $producto, 
        Sede $sede, 
        InventarioSede $inventarioSede,
        float $saldoAnterior, 
        float $saldoNuevo,
        array $datos,
        string $tipoMovimiento = null  // 🔥 Parámetro opcional
    ): void {
        $diferencia = $saldoNuevo - $saldoAnterior;
        
        if ($diferencia == 0) {
            return;
        }

        // Traducir 'entrada' y 'salida' a los tipos válidos en la base de datos de Postgres ('ajuste_entrada' y 'ajuste_salida')
        if ($tipoMovimiento === null) {
            $tipoMovimiento = $diferencia > 0 ? 'ajuste_entrada' : 'ajuste_salida';
        } else {
            if ($tipoMovimiento === 'entrada') {
                $tipoMovimiento = 'ajuste_entrada';
            } elseif ($tipoMovimiento === 'salida') {
                $tipoMovimiento = 'ajuste_salida';
            }
        }

        $costoUnitario = !empty($datos['costo_unitario']) 
            ? floatval($datos['costo_unitario']) 
            : ($producto->precio_compra ?? 0);

        if ($costoUnitario == 0) {
            $costoUnitario = $this->cache['ultimos_costos'][$producto->id] ?? 0;
        }

        $cantidadMovimiento = abs($diferencia);

        // 🔥 Determinar la nota según el modo
        $nota = 'Carga Inicial de Inventario';
        if ($this->stats['modo'] === 'actualizar') {
            if ($tipoMovimiento === 'entrada') {
                $nota = "Actualización de stock (+{$cantidadMovimiento} unidades)";
            } else {
                $nota = "Actualización de stock (-{$cantidadMovimiento} unidades)";
            }
        }

        Log::debug("   📝 Registrando Kardex: {$producto->nombre} - {$tipoMovimiento} - {$cantidadMovimiento} unidades");

        try {
            $kardex = new KardexMovimiento();
            $kardex->sede_id = $sede->id;
            $kardex->producto_id = $producto->id;
            $kardex->tipo_movimiento = $tipoMovimiento;  // 🔥 'entrada' o 'salida'
            $kardex->cantidad = $cantidadMovimiento;
            $kardex->saldo_anterior = $saldoAnterior;
            $kardex->saldo_despues = $saldoNuevo;
            $kardex->costo_unitario = $costoUnitario;
            $kardex->costo_total = $cantidadMovimiento * $costoUnitario;
            $kardex->documento_origen_type = get_class($inventarioSede);
            $kardex->documento_origen_id = $inventarioSede->id;
            $kardex->notas = $nota;  // 🔥 Nota personalizada
            $kardex->created_by = auth()->id() ?? 1;
            $kardex->saveOrFail();

            $this->stats['kardex_registrados']++;

            if ($costoUnitario > 0) {
                $this->cache['ultimos_costos'][$producto->id] = $costoUnitario;
            }
            
        } catch (\Exception $e) {
            Log::error("❌ Error al guardar Kardex para {$producto->nombre}: " . $e->getMessage());
            throw new Exception("Error al guardar Kardex: " . $e->getMessage());
        }
    }

    // ===================== MÉTODOS AUXILIARES =====================

    private function validarArchivo(string $filePath): void
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new Exception("El archivo no existe o no es legible.");
        }
        
        $size = filesize($filePath);
        Log::info("   📦 Tamaño del archivo: " . number_format($size / 1024, 2) . " KB");
    }

    private function validarSede(int $sedeId): Sede
    {
        $sede = Sede::find($sedeId);
        if (!$sede) {
            throw new Exception("La sede con ID '{$sedeId}' no existe.");
        }
        return $sede;
    }

    private function parsearCSV(string $filePath): array
    {
        $file = fopen($filePath, 'r');
        if (!$file) {
            throw new Exception("No se pudo abrir el archivo.");
        }

        $delimiter = $this->detectarDelimitador($file);
        rewind($file);
        Log::info("   🔍 Delimitador detectado: '{$delimiter}'");

        $headers = fgetcsv($file, 0, $delimiter);
        if (!$headers) {
            fclose($file);
            throw new Exception("El archivo está vacío o no tiene cabeceras.");
        }

        $headers = array_map(function($header) {
            return trim(preg_replace('/[\x{FEFF}\x{FFFE}]/u', '', $header));
        }, $headers);

        $mapa = $this->mapearEncabezados($headers);
        Log::info("   📋 Cabeceras detectadas: " . implode(', ', $headers));

        $camposRequeridos = ['producto_id', 'cantidad_inicial'];
        foreach ($camposRequeridos as $campo) {
            if (!isset($mapa[$campo])) {
                fclose($file);
                throw new Exception("Campo requerido no encontrado: '{$campo}'. Cabeceras: " . implode(', ', $headers));
            }
        }

        $rows = [];
        $lineNumber = 1;

        while (($row = fgetcsv($file, 0, $delimiter)) !== false) {
            $lineNumber++;
            
            if (empty($row) || (count($row) === 1 && empty($row[0]))) {
                continue;
            }

            $rowData = [];
            foreach ($mapa as $campo => $indice) {
                $rowData[$campo] = isset($row[$indice]) ? trim($row[$indice]) : '';
            }

            if (empty($rowData['producto_id']) && empty($rowData['codigo'])) {
                continue;
            }

            $rows[] = [
                'linea' => $lineNumber,
                'datos' => $rowData,
            ];
        }
        fclose($file);

        Log::info("   📊 {$lineNumber} líneas leídas, " . count($rows) . " con datos válidos");

        return [
            'headers' => $headers,
            'mapa' => $mapa,
            'rows' => $rows,
        ];
    }

    private function detectarDelimitador($file): string
    {
        $firstLine = fgets($file);
        rewind($file);
        
        $delimiters = [';', ',', "\t"];
        foreach ($delimiters as $d) {
            if (strpos($firstLine, $d) !== false) {
                return $d;
            }
        }
        
        return ';';
    }

    private function mapearEncabezados(array $headers): array
    {
        $mapa = [];
        $headersLower = array_map('strtolower', $headers);

        $campos = [
            'producto_id' => ['producto_id', 'id_producto', 'id'],
            'codigo' => ['codigo', 'sku', 'codigo_producto'],
            'nombre' => ['nombre', 'producto_nombre', 'nombre_producto'],
            'cantidad_inicial' => ['cantidad_inicial', 'stock_inicial', 'cantidad', 'stock'],
            'stock_minimo' => ['stock_minimo', 'minimo', 'min'],
            'stock_maximo' => ['stock_maximo', 'maximo', 'max'],
            'punto_reorden' => ['punto_reorden', 'reorden'],
            'costo_unitario' => ['costo_unitario', 'costo_promedio', 'costo'],
        ];

        foreach ($campos as $campo => $sinonimos) {
            foreach ($sinonimos as $sinonimo) {
                $index = array_search(strtolower($sinonimo), $headersLower);
                if ($index !== false) {
                    $mapa[$campo] = $index;
                    break;
                }
            }
        }

        return $mapa;
    }

    private function cargarCache(int $sedeId): void
    {
        $this->sedeId = $sedeId;
        $productos = Producto::withoutGlobalScope('sede')->where('sede_id', $sedeId)->get();
        foreach ($productos as $producto) {
            $this->cache['productos'][$producto->id] = $producto;
            if ($producto->codigo) {
                $this->cache['productos_por_codigo'][$producto->codigo] = $producto;
            }
        }
        Log::info("   📦 {$productos->count()} productos cargados en caché para la sede {$sedeId}");
        
        $stocks = InventarioSede::withTrashed()
            ->where('sede_id', $sedeId)
            ->get();
            
        $this->cache['stocks'] = [];
        $this->cache['stock_existente'] = [];
        
        foreach ($stocks as $stock) {
            $this->cache['stocks'][$stock->producto_id] = $stock;
            if (!$stock->trashed()) {
                $this->cache['stock_existente'][] = $stock->producto_id;
            }
        }
        Log::info("   📦 {$stocks->count()} registros de stock cargados en caché");
        
        $this->cache['has_kardex'] = KardexMovimiento::where('sede_id', $sedeId)
            ->pluck('producto_id')
            ->unique()
            ->toArray();
        Log::info("   📦 " . count($this->cache['has_kardex']) . " productos con movimientos Kardex");

        $this->cache['ultimos_costos'] = KardexMovimiento::where('sede_id', $sedeId)
            ->whereNotNull('costo_unitario')
            ->where('costo_unitario', '>', 0)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('producto_id')
            ->pluck('costo_unitario', 'producto_id')
            ->toArray();
        Log::info("   📦 " . count($this->cache['ultimos_costos']) . " productos con costo registrado");
    }

    private function buscarProducto(array $datos, int $linea): ?Producto
    {
        if (!empty($datos['producto_id']) && is_numeric($datos['producto_id'])) {
            $productoId = intval($datos['producto_id']);
            if (isset($this->cache['productos'][$productoId])) {
                return $this->cache['productos'][$productoId];
            }
            
            $producto = Producto::withoutGlobalScope('sede')
                ->where('sede_id', $this->sedeId)
                ->find($productoId);
            if ($producto) {
                $this->cache['productos'][$productoId] = $producto;
                if ($producto->codigo) {
                    $this->cache['productos_por_codigo'][$producto->codigo] = $producto;
                }
                return $producto;
            }
        }

        if (!empty($datos['codigo'])) {
            $codigo = trim($datos['codigo']);
            if (isset($this->cache['productos_por_codigo'][$codigo])) {
                return $this->cache['productos_por_codigo'][$codigo];
            }
            
            $producto = Producto::withoutGlobalScope('sede')
                ->where('sede_id', $this->sedeId)
                ->where('codigo', $codigo)
                ->first();
            if ($producto) {
                $this->cache['productos'][$producto->id] = $producto;
                $this->cache['productos_por_codigo'][$codigo] = $producto;
                return $producto;
            }
        }

        if (!empty($datos['nombre'])) {
            $nombreBuscado = trim($datos['nombre']);
            $producto = Producto::withoutGlobalScope('sede')
                ->where('sede_id', $this->sedeId)
                ->where(function ($q) use ($nombreBuscado) {
                    $q->where('nombre', $nombreBuscado)
                      ->orWhereRaw('LOWER(TRIM(nombre)) = ?', [strtolower($nombreBuscado)]);
                })
                ->first();
            if ($producto) {
                $this->cache['productos'][$producto->id] = $producto;
                if ($producto->codigo) {
                    $this->cache['productos_por_codigo'][$producto->codigo] = $producto;
                }
                return $producto;
            }
        }

        return null;
    }

    private function validarCantidad($cantidadRaw, int $linea, Producto $producto): float
    {
        if (empty($cantidadRaw) && $cantidadRaw !== '0') {
            throw new Exception("La cantidad es requerida");
        }

        $cantidadClean = str_replace(',', '.', $cantidadRaw);
        if (!is_numeric($cantidadClean)) {
            throw new Exception("Cantidad inválida: '{$cantidadRaw}'");
        }

        $cantidad = floatval($cantidadClean);
        if ($cantidad < 0) {
            throw new Exception("La cantidad no puede ser negativa: {$cantidad}");
        }

        return $cantidad;
    }
}