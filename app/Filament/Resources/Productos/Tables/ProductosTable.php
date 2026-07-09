<?php

namespace App\Filament\Resources\Productos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Catalog\Categoria;

class ProductosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codigo')
                    ->label('Código / SKU')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('categoria.nombre')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'venta' => 'success',
                        'subensamble' => 'warning',
                        'insumo' => 'info',
                    })
                    ->sortable(),

                TextColumn::make('unidadMedida.nombre')
                    ->label('Unidad')
                    ->sortable(),

                TextColumn::make('precio_unitario')
                    ->label('Precio')
                    ->money('COP')
                    ->sortable(),

                IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('categoria_id')
                    ->label('Categoría')
                    ->options(Categoria::pluck('nombre', 'id')->toArray()),

                SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'venta' => 'Venta',
                        'subensamble' => 'Subensamble',
                        'insumo' => 'Insumo',
                    ]),

                SelectFilter::make('activo')
                    ->label('Estado')
                    ->options([
                        '1' => 'Activos',
                        '0' => 'Inactivos',
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
