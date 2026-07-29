<?php

namespace App\Filament\Resources\AprobacionCompras\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\Catalog\Sede;
use App\Models\Catalog\Proveedor;

class AprobacionComprasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_factura')
                    ->label('Factura #')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('proveedor.nombre')
                    ->label('Proveedor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('fecha_factura')
                    ->label('Fecha Factura')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('COP')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'borrador' => 'gray',
                        'pendiente' => 'warning',
                        'aprobado' => 'success',
                        'rechazado' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'borrador' => '📝 Borrador',
                        'pendiente' => '⏳ Pendiente',
                        'aprobado' => '✅ Aprobado',
                        'rechazado' => '❌ Rechazado',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('imagen_factura')
                    ->label('Soporte')
                    ->formatStateUsing(fn ($state) => empty($state) ? '---' : (str_ends_with(strtolower($state), '.pdf') ? '📄 PDF' : '🖼️ Imagen'))
                    ->color(fn ($state) => empty($state) ? 'gray' : 'info')
                    ->url(fn ($record) => $record->imagen_factura ? asset('storage/' . $record->imagen_factura) : null)
                    ->openUrlInNewTab()
                    ->badge()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pendiente' => '⏳ Pendiente',
                        'aprobado'  => '✅ Aprobado',
                        'rechazado' => '❌ Rechazado',
                    ]),

                SelectFilter::make('proveedor_id')
                    ->label('Proveedor')
                    ->options(Proveedor::pluck('nombre', 'id')->toArray()),
            ])
            ->actions([
                ViewAction::make(),
                Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('¿Aprobar esta compra/factura?')
                    ->modalDescription('Esta acción cambiará el estado de la compra a "Aprobado", sumará el stock de los productos en la sede correspondiente y registrará los movimientos en el Kardex. Esta acción no se puede deshacer.')
                    ->visible(fn ($record) => $record->status === 'pendiente' && auth()->user()?->hasPermissionTo('compra.aprobar'))
                    ->action(function ($record) {
                        $record->aprobar();
                        
                        Notification::make()
                            ->title('Compra aprobada')
                            ->body('El stock de los productos ha sido actualizado y se han registrado los movimientos en el Kardex.')
                            ->success()
                            ->send();
                    }),
                Action::make('devolver')
                    ->label('Devolver')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('¿Devolver esta compra a Borrador?')
                    ->modalDescription('Esta acción cambiará el estado de la compra a "Borrador" para que el usuario creador pueda modificarla o corregirla.')
                    ->visible(fn ($record) => $record->status === 'pendiente' && auth()->user()?->hasPermissionTo('compra.aprobar'))
                    ->action(function ($record) {
                        $record->status = 'borrador';
                        $record->save();
                        
                        Notification::make()
                            ->title('Compra devuelta')
                            ->body('La compra ha sido devuelta a estado borrador para su edición.')
                            ->warning()
                            ->send();
                    }),
                Action::make('rechazar')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('¿Rechazar esta compra?')
                    ->modalDescription('Esta acción marcará la compra como "Rechazada". No se actualizará el stock ni se registrarán movimientos de Kardex. Esta acción es definitiva.')
                    ->visible(fn ($record) => $record->status === 'pendiente' && auth()->user()?->hasPermissionTo('compra.aprobar'))
                    ->action(function ($record) {
                        $record->status = 'rechazado';
                        $record->save();
                        
                        Notification::make()
                            ->title('Compra rechazada')
                            ->body('La compra ha sido rechazada y guardada como histórico.')
                            ->danger()
                            ->send();
                    })
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->headerActions([
                Action::make('exportExcel')
                    ->label('Descargar Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($livewire) {
                        $query = $livewire->getFilteredTableQuery();
                        return \Maatwebsite\Excel\Facades\Excel::download(
                            new \App\Exports\ComprasExport($query),
                            'reporte_aprobacion_compras_' . now()->format('Y-m-d_H-i') . '.xlsx'
                        );
                    }),
            ])
            ->bulkActions([
                // Las aprobaciones no se borran en lote por seguridad
            ]);
    }
}
