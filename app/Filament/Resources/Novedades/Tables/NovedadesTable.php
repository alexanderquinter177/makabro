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

                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'caída/quiebre' => 'warning',
                        'quemado' => 'danger',
                        'vencimiento' => 'info',
                        'daño' => 'danger',
                        'devolución' => 'primary',
                        'pérdida/robo' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('area')
                    ->label('Área')
                    ->sortable(),

                TextColumn::make('producto.nombre')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('valor_costo')
                    ->label('Costo')
                    ->money('COP')
                    ->sortable(),

                TextColumn::make('valor_cobro')
                    ->label('Cobro')
                    ->money('COP')
                    ->sortable(),

                TextColumn::make('estado_cobro')
                    ->label('Cobro Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'si' => 'success',
                        'no' => 'gray',
                        'pendiente' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

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
