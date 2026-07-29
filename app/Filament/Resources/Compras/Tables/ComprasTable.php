<?php

namespace App\Filament\Resources\Compras\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\Catalog\Sede;
use App\Models\Catalog\Proveedor;

class ComprasTable
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
                        'borrador'  => '📝 Borrador',
                        'pendiente' => '⏳ Pendiente de Aprobación',
                        'aprobado'  => '✅ Aprobado',
                        'rechazado' => '❌ Rechazado',
                    ]),

                SelectFilter::make('proveedor_id')
                    ->label('Proveedor')
                    ->options(Proveedor::pluck('nombre', 'id')->toArray()),

                SelectFilter::make('registro_tardio')
                    ->label('Registro Tardío')
                    ->options([
                        '1' => 'Sí',
                        '0' => 'No',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn ($record) => $record->status === 'borrador'),
                Action::make('enviar')
                    ->label('Presentar')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalHeading('¿Presentar esta compra para aprobación?')
                    ->modalDescription('Esta acción cambiará el estado de la compra a "Pendiente de Aprobación" para que los administradores puedan revisarla y aprobarla.')
                    ->visible(fn ($record) => $record->status === 'borrador')
                    ->action(function ($record) {
                        $record->status = 'pendiente';
                        $record->save();
                        
                        Notification::make()
                            ->title('Compra presentada')
                            ->body('La compra ha sido enviada para aprobación.')
                            ->success()
                            ->send();
                    }),
            ])
            ->recordUrl(function ($record) {
                if ($record->status === 'borrador') {
                    return \App\Filament\Resources\CompraResource::getUrl('edit', ['record' => $record]);
                }
                return \App\Filament\Resources\CompraResource::getUrl('view', ['record' => $record]);
            })
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
                            'reporte_compras_' . now()->format('Y-m-d_H-i') . '.xlsx'
                        );
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
