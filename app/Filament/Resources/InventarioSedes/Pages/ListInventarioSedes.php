<?php

namespace App\Filament\Resources\InventarioSedes\Pages;

use App\Filament\Resources\InventarioSedeResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use App\Models\Catalog\Producto;
use App\Models\Catalog\Sede;
use App\Models\Inventory\InventarioSede;
use App\Services\Inventory\CargaInicialImporter;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;

class ListInventarioSedes extends ListRecords
{
    protected static string $resource = InventarioSedeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('descargarPlantilla')
                ->label('Descargar Plantilla')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->action(function () {
                    // Evitar límites de tiempo de ejecución y aumentar memoria para la descarga
                    @set_time_limit(0);
                    @ini_set('memory_limit', '512M');
                    
                    $sedeId = session('sede_id');
                    $sede = $sedeId ? Sede::find($sedeId) : null;
                    $sedeNombre = $sede ? str_replace(' ', '_', strtolower($sede->nombre)) : 'general';
                    $fileName = 'plantilla_carga_inicial_' . $sedeNombre . '.csv';

                    return response()->streamDownload(function () use ($sedeId) {
                        $output = fopen('php://output', 'w');
                        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
                        
                        // Cabeceras en MAYÚSCULAS
                        fputcsv($output, ['PRODUCTO_ID', 'CODIGO', 'NOMBRE', 'UNIDAD_MEDIDA', 'CANTIDAD_INICIAL', 'STOCK_MINIMO', 'STOCK_MAXIMO'], ';');
                        
                        // 1. Cargar en memoria todos los stocks de la sede de una sola vez
                        $stocksMap = InventarioSede::where('sede_id', $sedeId)
                            ->get()
                            ->keyBy('producto_id');

                        // 2. Procesar los insumos ordenados por categoría y luego por nombre del producto
                        Producto::where('productos.tipo', 'insumo')
                            ->where('productos.activo', true)
                            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
                            ->orderBy('categorias.nombre')
                            ->orderBy('productos.nombre')
                            ->select('productos.*')
                            ->chunk(250, function ($insumos) use ($output, $stocksMap) {
                                foreach ($insumos as $insumo) {
                                    // Obtener stock desde el mapa cargado en memoria (0 consultas adicionales)
                                    $stock = $stocksMap->get($insumo->id);

                                    $cantidad = $stock ? floatval($stock->cantidad_actual) : 0;
                                    $minimo = $stock ? floatval($stock->stock_minimo) : 0;
                                    $maximo = $stock ? floatval($stock->stock_maximo) : 0;
                                    $unidad = $insumo->unidadCompra ? $insumo->unidadCompra->abreviatura : '---';

                                    fputcsv($output, [
                                        $insumo->id,
                                        strtoupper($insumo->codigo),
                                        strtoupper($insumo->nombre),
                                        strtoupper($unidad),
                                        $cantidad,
                                        $minimo,
                                        $maximo
                                    ], ';');
                                }
                            });
                        
                        fclose($output);
                    }, $fileName, [
                        'Content-Type' => 'text/csv; charset=UTF-8',
                    ]);
                }),

            Action::make('importarCargaInicial')
                ->label('Importar Carga Inicial')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->form([
                    FileUpload::make('archivo_csv')
                        ->label('Seleccionar Archivo CSV (Delimitado por punto y coma ";" o comas ",")')
                        ->required()
                        ->disk('local')
                        ->directory('temp-imports')
                        ->maxSize(20480)
                        ->acceptedFileTypes([
                            'text/csv',
                            'text/plain',
                            'text/x-csv',
                            'application/csv',
                            'application/x-csv',
                            'text/comma-separated-values',
                            'text/x-comma-separated-values',
                            'application/vnd.ms-excel',
                            'application/vnd.msexcel',
                            'application/excel',
                            'application/octet-stream',
                        ]),
                ])
                ->action(function (array $data) {
                    // 🔥 AUMENTAR TIEMPO DE EJECUCIÓN Y MEMORIA
                    @set_time_limit(300); // 5 minutos
                    @ini_set('memory_limit', '512M');
                    
                    $filePath = Storage::disk('local')->path($data['archivo_csv']);
                    $sedeId = session('sede_id');

                    if (!$sedeId) {
                        Notification::make()
                            ->title('Sede no seleccionada')
                            ->body('Por favor, selecciona una sede en el menú superior antes de realizar la importación.')
                            ->danger()
                            ->send();
                        return;
                    }
                    
                    try {
                        $sede = Sede::find($sedeId);
                        
                        // 🔥 PROCESAR CON CHUNKS PARA OPTIMIZAR
                        $importer = new CargaInicialImporter();
                        $result = $importer->import($filePath, $sedeId);
                        
                        if (isset($result['success']) && !$result['success']) {
                            throw new \Exception($result['message'] ?? 'Error desconocido en la importación.');
                        }
                        
                        // Construir mensaje detallado
                        $mensaje = "✅ Importación completada exitosamente en la sede '{$sede->nombre}'\n\n";
                        $mensaje .= "📊 Productos procesados: {$result['stats']['productos_procesados']}\n";
                        $mensaje .= "📊 Stock actualizado: {$result['stats']['stock_actualizado']}\n";
                        $mensaje .= "📊 Movimientos Kardex: {$result['stats']['kardex_registrados']}\n";
                        
                        if (!empty($result['stats']['errores'])) {
                            $mensaje .= "⚠️ Errores: " . count($result['stats']['errores']) . "\n\n";
                            // Mostrar los primeros 5 errores
                            foreach (array_slice($result['stats']['errores'], 0, 5) as $error) {
                                $mensaje .= "• Línea {$error['linea']}: {$error['mensaje']}\n";
                            }
                            if (count($result['stats']['errores']) > 5) {
                                $mensaje .= "... y " . (count($result['stats']['errores']) - 5) . " errores más";
                            }
                        }
                        
                        Notification::make()
                            ->title('✅ Importación Exitosa')
                            ->body($mensaje)
                            ->success()
                            ->persistent()
                            ->send();

                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('❌ Error en Carga Inicial')
                            ->body($e->getMessage())
                            ->danger()
                            ->persistent()
                            ->send();
                    } finally {
                        // Limpiar archivo temporal
                        if (file_exists($filePath)) {
                            try {
                                unlink($filePath);
                            } catch (\Exception $e) {
                                // Ignorar error al eliminar archivo
                            }
                        }
                    }
                }),

            CreateAction::make(),
        ];
    }
}
