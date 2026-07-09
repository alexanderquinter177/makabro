<?php

namespace App\Filament\Resources\InventarioSedes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use App\Models\Catalog\Sede;
use App\Models\Catalog\Producto;
use Illuminate\Database\Eloquent\Builder;

class InventarioSedesTable
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

                TextColumn::make('cantidad_actual')
                    ->label('Cantidad Actual')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($record) => $record->cantidad_actual <= $record->stock_minimo ? 'danger' : 'success')
                    ->weight(fn ($record) => $record->cantidad_actual <= $record->stock_minimo ? 'bold' : 'normal'),

                TextColumn::make('stock_minimo')
                    ->label('Stock Mínimo')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('stock_maximo')
                    ->label('Stock Máximo')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('punto_reorden')
                    ->label('Punto de Reorden')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sugerido_compra')
                    ->label('Sugerido Compra')
                    ->getStateUsing(fn ($record) => $record->sugerido_compra)
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('sede_id')
                    ->label('Sede')
                    ->options(Sede::pluck('nombre', 'id')->toArray()),

                SelectFilter::make('producto_id')
                    ->label('Producto')
                    ->options(Producto::pluck('nombre', 'id')->toArray()),

                Filter::make('bajo_stock_minimo')
                    ->label('Bajo Stock Mínimo')
                    ->query(fn (Builder $query) => $query->bajoStockMinimo()),

                Filter::make('en_punto_reorden')
                    ->label('En Punto de Reorden')
                    ->query(fn (Builder $query) => $query->enPuntoDeReorden()),
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
