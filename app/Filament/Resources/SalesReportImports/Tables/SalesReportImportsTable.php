<?php

namespace App\Filament\Resources\SalesReportImports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Catalog\Sede;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SalesReportImportClass;

class SalesReportImportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-building-storefront')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('date_range')
                    ->label('Rango de Fechas')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-calendar-date-range')
                    ->badge()
                    ->color('success'),

                TextColumn::make('file_name')
                    ->label('Archivo Importado')
                    ->searchable()
                    ->limit(35)
                    ->tooltip(fn ($record) => $record->file_name)
                    ->icon('heroicon-o-paper-clip')
                    ->color('gray')
                    ->fontFamily('mono'),

                // ── Columnas de resumen calculadas ───────────────────────────────
                TextColumn::make('items_total')
                    ->label('Líneas')
                    ->state(fn ($record) => $record->items()->count())
                    ->badge()
                    ->color('gray')
                    ->sortable(false),

                TextColumn::make('items_ok')
                    ->label('✅ Procesadas')
                    ->state(fn ($record) => $record->items()->whereNotNull('product_id')->count())
                    ->badge()
                    ->color('success')
                    ->sortable(false),

                TextColumn::make('items_nok')
                    ->label('⚠️ Sin stock')
                    ->state(fn ($record) => $record->items()->whereNull('product_id')->count())
                    ->badge()
                    ->color(fn ($record) => $record->items()->whereNull('product_id')->count() > 0 ? 'danger' : 'gray')
                    ->sortable(false),

                TextColumn::make('created_at')
                    ->label('Importado el')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->icon('heroicon-o-clock')
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('sede_id')
                    ->label('Sede')
                    ->options(Sede::pluck('nombre', 'id')->toArray()),
            ])
            ->headerActions([
                Action::make('importarReporteVentas')
                    ->label('Importar Reporte de Ventas')
                    ->icon('heroicon-o-document-arrow-up')
                    ->color('success')
                    ->modalHeading('📂 Cargar Reporte de Ventas Excel')
                    ->modalDescription('Selecciona el archivo Excel exportado del sistema de ventas. El sistema cruzará los productos con el stock de la sede activa.')
                    ->modalIcon('heroicon-o-table-cells')
                    ->form([
                        FileUpload::make('archivo_excel')
                            ->label('Archivo de Reporte de Ventas (Excel)')
                            ->required()
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-excel',
                                'application/octet-stream'
                            ])
                            ->disk('local')
                            ->directory('imports')
                            ->visibility('private')
                            ->storeFileNamesIn('archivo_excel_original_name')
                    ])
                    ->action(function (array $data) {
                        $file = $data['archivo_excel'];
                        $filePath = is_array($file) ? reset($file) : $file;

                        $originalNameField = $data['archivo_excel_original_name'] ?? null;
                        $originalName = is_array($originalNameField) ? reset($originalNameField) : $originalNameField;
                        if (!$originalName) {
                            $originalName = basename($filePath);
                        }

                        if (!$filePath) {
                            Notification::make()
                                ->title('Error al subir el archivo')
                                ->body('No se pudo encontrar la ruta del archivo subido.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $sedeId = session('sede_id') ?? auth()->user()?->sede_id_actual;
                        if (!$sedeId) {
                            Notification::make()
                                ->title('Sede no seleccionada')
                                ->body('Por favor, selecciona una sede antes de importar el reporte.')
                                ->danger()
                                ->send();
                            return;
                        }

                        try {
                            $importClass = new SalesReportImportClass((int) $sedeId, $originalName);

                            Excel::import($importClass, $filePath, 'local');

                            // 1. Éxito general con listado de productos que cargaron
                            $loaded = $importClass->getLoadedProducts();
                            $loadedList = !empty($loaded) ? implode(', ', array_unique($loaded)) : 'Ninguno';

                            Notification::make()
                                ->title('✅ Reporte de Ventas procesado')
                                ->body("Se actualizaron las existencias de: {$loadedList}")
                                ->success()
                                ->persistent()
                                ->send();

                            // 2. Productos sin stock en la sede (Advertencia con formato exacto)
                            $notFound = $importClass->getNotFoundProducts();
                            if (!empty($notFound)) {
                                $list = implode(', ', array_unique($notFound));
                                Notification::make()
                                    ->title('⚠️ Productos NO procesados')
                                    ->body("Los siguientes productos no tienen stock registrado en esta sede y no fueron procesados: {$list}")
                                    ->warning()
                                    ->persistent()
                                    ->send();
                            }

                            // 3. Cantidades menores reportadas
                            $alertList = $importClass->getAlertProducts();
                            if (!empty($alertList)) {
                                $list = implode(', ', array_unique($alertList));
                                Notification::make()
                                    ->title('🚫 Ajustes de Cantidad Menor Ignorados')
                                    ->body("Los siguientes productos reportaron menor cantidad que el histórico y no se actualizaron: {$list}")
                                    ->danger()
                                    ->persistent()
                                    ->send();
                            }

                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error en la importación')
                                ->body($e->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    })
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Ver Detalle'),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateHeading('Sin reportes de ventas importados')
            ->emptyStateDescription('Usa el botón "Importar Reporte de Ventas" para cargar el primer reporte Excel.');
    }
}
