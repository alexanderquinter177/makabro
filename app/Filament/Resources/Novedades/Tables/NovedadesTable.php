<?php

namespace App\Filament\Resources\Novedades\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Catalog\Sede;
use App\Models\Catalog\Producto;
use App\Models\Auth\User;
use Filament\Actions\Action;
use App\Models\Inventory\Novedad;

class NovedadesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('responsable_nombre')
                    ->label('Responsable')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'caída/quiebre' => 'warning',
                        'quemado' => 'danger',
                        'vencimiento' => 'info',
                        'daño' => 'danger',
                        'devolución' => 'gray',
                        'pérdida/robo' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('area')
                    ->label('Área')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipo_afectado')
                    ->label('Afectado')
                    ->searchable(),

                TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('valor_costo')
                    ->label('Valor Costo')
                    ->money('COP')
                    ->sortable(),

                TextColumn::make('valor_cobro')
                    ->label('Valor Cobro')
                    ->money('COP')
                    ->sortable(),

                TextColumn::make('estado_cobro')
                    ->label('Estado Cobro')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'si' => 'danger',
                        'no' => 'success',
                        'pendiente' => 'warning',
                        default => 'gray',
                    }),

                ImageColumn::make('evidencia_imagen')
                    ->label('Evidencia')
                    ->square(),

                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('sede_id')
                    ->label('Sede')
                    ->options(Sede::pluck('nombre', 'id')->toArray()),

                SelectFilter::make('tipo')
                    ->label('Tipo Novedad')
                    ->options([
                        'caída/quiebre' => 'Caída / Quiebre',
                        'quemado' => 'Quemado',
                        'vencimiento' => 'Vencimiento',
                        'daño' => 'Daño',
                        'devolución' => 'Devolución',
                        'pérdida/robo' => 'Pérdida / Robo',
                    ]),

                SelectFilter::make('estado_cobro')
                    ->label('Estado Cobro')
                    ->options([
                        'si' => 'Cobrado',
                        'no' => 'No aplica',
                        'pendiente' => 'Pendiente',
                    ]),
            ])
            ->recordActions([
                Action::make('imprimir')
                    ->label('Imprimir Carta')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (Novedad $record): string => route('novedades.imprimir', $record))
                    ->openUrlInNewTab(),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
