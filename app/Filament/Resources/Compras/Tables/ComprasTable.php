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

                IconColumn::make('registro_tardio')
                    ->label('Tardía')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('sede_id')
                    ->label('Sede')
                    ->options(Sede::pluck('nombre', 'id')->toArray()),

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
