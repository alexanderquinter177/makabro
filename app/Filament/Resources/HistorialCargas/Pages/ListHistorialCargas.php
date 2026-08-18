<?php

namespace App\Filament\Resources\HistorialCargas\Pages;

use App\Filament\Resources\HistorialCargaResource;
use App\Models\Inventory\CargaHistorial;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListHistorialCargas extends ListRecords
{
    protected static string $resource = HistorialCargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // 1. Botón para descargar la plantilla CSV con los productos configurados
            Action::make('descargarPlantilla')
                ->label('Descargar Plantilla CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function (): StreamedResponse {
                    $headers = [
                        'Content-Type' => 'text/csv; charset=UTF-8',
                        'Content-Disposition' => 'attachment; filename="plantilla_carga_productos_' . date('Ymd_His') . '.csv"',
                    ];

                    $sedeId = session('sede_id') ?? auth()->user()?->sede_id_actual ?? auth()->user()?->sede_id;

                    $callback = function () use ($sedeId) {
                        $file = fopen('php://output', 'w');
                        // UTF-8 BOM para soporte correcto en Excel
                        fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
                        fputcsv($file, ['CODIGO', 'NOMBRE_PRODUCTO', 'CATEGORIA', 'TIPO_PRODUCTO', 'UNIDAD_MEDIDA', 'CANTIDAD', 'PRECIO'], ';');

                        // Obtener todos los productos configurados en el catálogo de la sede activa
                        $query = \App\Models\Catalog\Producto::withoutGlobalScope('sede')
                            ->with(['categoria', 'unidadCompra'])
                            ->where('activo', true);

                        if ($sedeId) {
                            $query->where('sede_id', $sedeId);
                        }

                        $productos = $query->orderBy('codigo')->get();

                        if ($productos->count() > 0) {
                            foreach ($productos as $producto) {
                                $codigo = mb_strtoupper(trim($producto->codigo ?? ''), 'UTF-8');
                                $nombre = mb_strtoupper(trim($producto->nombre ?? ''), 'UTF-8');
                                $categoria = mb_strtoupper(trim($producto->categoria?->nombre ?? 'GENERAL'), 'UTF-8');
                                $tipo = mb_strtoupper(trim($producto->tipo ?? 'INSUMO'), 'UTF-8');
                                $unidad = mb_strtoupper(trim($producto->unidadCompra?->abreviatura ?? $producto->unidadCompra?->nombre ?? 'UND'), 'UTF-8');
                                $cantidad = "0";
                                
                                $precioVal = (float) ($producto->precio_compra ?? 0);
                                $precioFormatted = str_replace('.', ',', (string) round($precioVal, 2));

                                fputcsv($file, [
                                    $codigo,
                                    $nombre,
                                    $categoria,
                                    $tipo,
                                    $unidad,
                                    $cantidad,
                                    $precioFormatted,
                                ], ';');
                            }
                        } else {
                            fputcsv($file, ['IN-ACE-002', 'ACEITE BIDON', 'ACEITES Y ABARROTES', 'INSUMO', 'ML', '0', '550'], ';');
                        }

                        fclose($file);
                    };

                    return response()->streamDownload($callback, 'plantilla_carga_productos.csv', $headers);
                }),

            // 2. Botón modal de importación de CSV
            Action::make('importarCarga')
                ->label('Importar Carga CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->modalHeading('Importar Carga de Productos')
                ->modalDescription('Carga un archivo CSV separado por punto y coma (;) para registrar un nuevo historial de carga.')
                ->form([
                    Select::make('sede_id')
                        ->label('Sede')
                        ->options(\App\Models\Catalog\Sede::where('activo', true)->pluck('nombre', 'id'))
                        ->required()
                        ->searchable()
                        ->default(fn () => session('sede_id') ?? auth()->user()?->sede_id_actual ?? auth()->user()?->sede_id)
                        ->disabled(true)
                        ->dehydrated()
                        ->prefixIcon('heroicon-o-building-office-2'),

                    DatePicker::make('fecha')
                        ->label('Fecha')
                        ->required()
                        ->default(now())
                        ->prefixIcon('heroicon-o-calendar'),

                    Select::make('tipo')
                        ->label('Tipo de Entrega')
                        ->options([
                            'Entrega de barra' => 'Entrega de barra',
                            'Entrega de cocina' => 'Entrega de cocina',
                        ])
                        ->required()
                        ->prefixIcon('heroicon-o-tag'),

                    Select::make('cargo_recibe')
                        ->label('Cargo de Quien Recibe')
                        ->options([
                            'Líder de cocina' => 'Líder de cocina',
                            'Administrador' => 'Administrador',
                        ])
                        ->required()
                        ->prefixIcon('heroicon-o-briefcase'),

                    TextInput::make('nombre_recibe')
                        ->label('Nombre de Quien Recibe')
                        ->placeholder('EJ: CARLOS GÓMEZ')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-user'),

                    FileUpload::make('archivo')
                        ->label('Archivo CSV')
                        ->disk('local')
                        ->directory('temp-imports')
                        ->acceptedFileTypes([
                            'text/csv',
                            'text/plain',
                            'text/tsv',
                            'application/vnd.ms-excel',
                            'application/csv',
                        ])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $filePath = Storage::disk('local')->path($data['archivo']);

                    if (!file_exists($filePath)) {
                        $filePath = storage_path('app/' . $data['archivo']);
                    }

                    if (!file_exists($filePath)) {
                        Notification::make()
                            ->title('Error al acceder al archivo')
                            ->body('No se pudo encontrar el archivo subido en el servidor.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $handle = fopen($filePath, 'r');
                    if ($handle === false) {
                        Notification::make()
                            ->title('Error de lectura')
                            ->body('No se pudo abrir el archivo CSV.')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Remover BOM si está presente
                    $bom = fread($handle, 3);
                    if ($bom !== "\xEF\xBB\xBF") {
                        rewind($handle);
                    }

                    $detalles = [];
                    $valorTotal = 0;
                    $firstRow = true;

                    while (($row = fgetcsv($handle, 0, ';')) !== false) {
                        if (empty($row) || (count($row) == 1 && trim($row[0]) === '')) {
                            continue;
                        }

                        // Omitir cabecera
                        if ($firstRow) {
                            $firstRow = false;
                            if (isset($row[0]) && strtoupper(trim($row[0])) === 'CODIGO') {
                                continue;
                            }
                        }

                        if (count($row) < 7) {
                            continue;
                        }

                        $codigo = mb_strtoupper(trim($row[0]), 'UTF-8');
                        $nombreProducto = mb_strtoupper(trim($row[1]), 'UTF-8');
                        $categoria = mb_strtoupper(trim($row[2]), 'UTF-8');
                        $tipoProducto = mb_strtoupper(trim($row[3]), 'UTF-8');
                        $unidadMedida = mb_strtoupper(trim($row[4]), 'UTF-8');

                        // Formatear cantidad (reemplazar coma por punto)
                        $rawCantidad = trim($row[5]);
                        $rawCantidad = str_replace(['$', ' '], '', $rawCantidad);
                        $rawCantidad = str_replace(',', '.', $rawCantidad);

                        // Formatear precio (reemplazar coma por punto para el cálculo)
                        $rawPrecio = trim($row[6]);
                        $rawPrecio = str_replace(['$', ' '], '', $rawPrecio);
                        if (strpos($rawPrecio, ',') !== false && strpos($rawPrecio, '.') !== false) {
                            $rawPrecio = str_replace('.', '', $rawPrecio);
                            $rawPrecio = str_replace(',', '.', $rawPrecio);
                        } else {
                            $rawPrecio = str_replace(',', '.', $rawPrecio);
                        }

                        $cantidad = (float) $rawCantidad;
                        $precio = (float) $rawPrecio;

                        $totalLinea = round($cantidad * $precio, 2);
                        $valorTotal += $totalLinea;

                        $detalles[] = [
                            'codigo' => $codigo,
                            'nombre_producto' => $nombreProducto,
                            'categoria' => $categoria,
                            'tipo_producto' => $tipoProducto,
                            'unidad_medida' => $unidadMedida,
                            'cantidad' => $cantidad,
                            'precio' => $precio,
                            'total_linea' => $totalLinea,
                        ];
                    }

                    fclose($handle);

                    if (empty($detalles)) {
                        Notification::make()
                            ->title('Sin datos válidos')
                            ->body('No se encontraron filas con el formato esperado (7 columnas separadas por ;).')
                            ->warning()
                            ->send();
                        return;
                    }

                    DB::transaction(function () use ($data, $detalles, $valorTotal) {
                        $maestro = CargaHistorial::create([
                            'sede_id' => $data['sede_id'] ?? session('sede_id') ?? auth()->user()?->sede_id_actual ?? auth()->user()?->sede_id,
                            'fecha' => $data['fecha'],
                            'tipo' => $data['tipo'],
                            'cargo_recibe' => mb_strtoupper(trim($data['cargo_recibe']), 'UTF-8'),
                            'nombre_recibe' => mb_strtoupper(trim($data['nombre_recibe']), 'UTF-8'),
                            'valor_total' => $valorTotal,
                        ]);

                        foreach ($detalles as $detalle) {
                            $maestro->productos()->create($detalle);
                        }
                    });

                    // Limpieza de archivo temporal
                    if (Storage::disk('local')->exists($data['archivo'])) {
                        Storage::disk('local')->delete($data['archivo']);
                    }

                    Notification::make()
                        ->title('Importación realizada con éxito')
                        ->body("Se registraron " . count($detalles) . " productos. Valor Total Maestro: $" . number_format($valorTotal, 2, ',', '.'))
                        ->success()
                        ->send();
                }),
        ];
    }
}
