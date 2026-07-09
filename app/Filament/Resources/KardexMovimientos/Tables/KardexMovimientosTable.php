<?php

namespace App\Filament\Resources\KardexMovimientos\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Catalog\Sede;
use App\Models\Catalog\Producto;

class KardexMovimientosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('producto.nombre')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipo_movimiento')
                    ->label('Tipo de Movimiento')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'entrada_compra' => 'success',
                        'salida_venta' => 'danger',
                        'ajuste_entrada' => 'info',
                        'ajuste_salida' => 'warning',
                        'merma_novedad' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->numeric(4)
                    ->sortable(),

                TextColumn::make('saldo_despues')
                    ->label('Saldo Después')
                    ->numeric(4)
                    ->sortable(),

                TextColumn::make('usuario.name')
                    ->label('Causado por')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('sede_id')
                    ->label('Sede')
                    ->options(Sede::pluck('nombre', 'id')->toArray()),

                SelectFilter::make('producto_id')
                    ->label('Producto')
                    ->options(Producto::pluck('nombre', 'id')->toArray()),

                SelectFilter::make('tipo_movimiento')
                    ->label('Tipo')
                    ->options([
                        'entrada_compra' => 'Entrada por Compra',
                        'salida_venta' => 'Salida por Venta',
                        'ajuste_entrada' => 'Ajuste de Entrada',
                        'ajuste_salida' => 'Ajuste de Salida',
                        'merma_novedad' => 'Merma / Novedad',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
