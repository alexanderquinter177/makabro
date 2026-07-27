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
use Illuminate\Support\Facades\Log;

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
                    $nombreSede = $sede ? \Illuminate\Support\Str::slug($sede->nombre) : 'general';
                    $fileName = "plantilla_carga_inicial_makabro_{$nombreSede}.csv";

                    return response()->streamDownload(function () use ($sedeId) {
                        $output = fopen('php://output', 'w');
                        
                        // BOM para UTF-8 en Excel
                        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
                        
                        // Encabezados
                        fputcsv($output, ['codigo', 'nombre_producto', 'cantidad_inicial', 'stock_minimo', 'stock_maximo'], ';');
                        
                        // Cargar TODOS los productos activos
                        Producto::query()
                            ->where('activo', true)
                            ->orderBy('nombre')
                            ->chunk(100, function ($productos) use ($output, $sedeId) {
                                foreach ($productos as $prod) {
                                    $inv = $sedeId ? InventarioSede::where('sede_id', $sedeId)->where('producto_id', $prod->id)->first() : null;
                                    
                                    $cantidad = $inv ? $inv->cantidad_actual : 0;
                                    $minimo   = $inv ? $inv->stock_minimo : 10;
                                    $maximo   = $inv ? $inv->stock_maximo : 100;

                                    fputcsv($output, [
                                        $prod->codigo,
                                        $prod->nombre,
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
                        ->maxSize(30720)
                        ->preserveFilenames(),
                ])
                ->action(function (array $data) {
                    @set_time_limit(600); // 10 minutos
                    @ini_set('memory_limit', '512M');
                    
                    $relativeFilePath = $data['archivo_csv'] ?? null;
                    
                    if (!$relativeFilePath) {
                        Log::error('❌ [CargaInicial] No se recibió el archivo subido.');
                        Notification::make()
                            ->title('Archivo no recibido')
                            ->body('No se pudo encontrar el archivo subido. Intenta seleccionarlo nuevamente.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $filePath = Storage::disk('local')->path($relativeFilePath);
                    
                    if (!file_exists($filePath)) {
                        Log::error("❌ [CargaInicial] Archivo no existe en disco: {$filePath}");
                        Notification::make()
                            ->title('Error de archivo')
                            ->body("El archivo subido no se encuentra en la ruta del servidor ({$relativeFilePath}). Por favor reintenta la subida.")
                            ->danger()
                            ->send();
                        return;
                    }

                    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['csv', 'txt'])) {
                        Log::warning("⚠️ [CargaInicial] Extensión no válida: .{$ext} en {$filePath}");
                        Notification::make()
                            ->title('Extensión no válida')
                            ->body("El archivo seleccionado (.{$ext}) debe ser un archivo de texto o hoja de cálculo .csv")
                            ->warning()
                            ->send();
                        return;
                    }

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
                        Log::info("🚀 [CargaInicial] Iniciando procesamiento de {$filePath} en Sede ID: {$sedeId}");
                        $sede = Sede::find($sedeId);
                        
                        $importer = new CargaInicialImporter();
                        $result = $importer->import($filePath, $sedeId);
                        
                        if (isset($result['success']) && !$result['success']) {
                            throw new \Exception($result['message'] ?? 'Error desconocido en la importación.');
                        }
                        
                        Log::info("✅ [CargaInicial] Importación exitosa en Sede '{$sede->nombre}':", $result['stats'] ?? []);

                        $mensaje = "✅ Importación completada en la sede '{$sede->nombre}'\n\n";
                        $mensaje .= "📊 Productos procesados: {$result['stats']['productos_procesados']}\n";
                        $mensaje .= "📊 Stock actualizado: {$result['stats']['stock_actualizado']}\n";
                        $mensaje .= "📊 Movimientos Kardex: {$result['stats']['kardex_registrados']}\n";
                        
                        if (!empty($result['stats']['errores'])) {
                            $mensaje .= "⚠️ Errores en filas: " . count($result['stats']['errores']) . "\n\n";
                            foreach (array_slice($result['stats']['errores'], 0, 5) as $error) {
                                $mensaje .= "• Línea {$error['linea']}: {$error['mensaje']}\n";
                            }
                            if (count($result['stats']['errores']) > 5) {
                                $mensaje .= "... y " . (count($result['stats']['errores']) - 5) . " errores más";
                            }
                        }
                        
                        Notification::make()
                            ->title('✅ Carga Inicial Procesada')
                            ->body($mensaje)
                            ->success()
                            ->persistent()
                            ->send();

                    } catch (\Throwable $e) {
                        Log::error("❌ [CargaInicial] Excepción durante la importación: " . $e->getMessage(), [
                            'archivo' => $filePath,
                            'trace' => $e->getTraceAsString(),
                        ]);

                        Notification::make()
                            ->title('❌ Error al procesar Carga Inicial')
                            ->body("Error durante el procesamiento: " . $e->getMessage())
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
