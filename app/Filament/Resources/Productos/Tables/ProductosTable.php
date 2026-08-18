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
use App\Models\Catalog\Producto;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ProductosTable
{
    public static function configure(Table $table, ?string $forceTipo = null): Table
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
                    ->sortable()
                    ->visible($forceTipo === null),

                TextColumn::make('unidadMedida.nombre')
                    ->label('Unidad')
                    ->sortable(),

                TextColumn::make('precio_compra')
                    ->label(fn () => match ($forceTipo) {
                        'venta' => 'Costo Producto',
                        'insumo' => 'Precio Compra',
                        'subensamble' => 'Costo Producción',
                        default => 'Costo / Precio',
                    })
                    ->money('COP')
                    ->sortable(),

                TextColumn::make('costo_venta')
                    ->label('Costo Venta')
                    ->money('COP')
                    ->sortable()
                    ->visible($forceTipo === 'venta' || $forceTipo === null)
                    ->toggleable(),

                IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters(array_filter([
                SelectFilter::make('categoria_id')
                    ->label('Categoría')
                    ->options(Categoria::pluck('nombre', 'id')->toArray()),

                $forceTipo === null ? SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'venta' => 'Venta',
                        'subensamble' => 'Subensamble',
                        'insumo' => 'Insumo',
                    ]) : null,

                SelectFilter::make('activo')
                    ->label('Estado')
                    ->options([
                        '1' => 'Activos',
                        '0' => 'Inactivos',
                    ]),
            ]))
            ->headerActions([
                Action::make('calcularCostosSubrecetas')
                    ->label('Calcular Costos Recetas y Subrecetas')
                    ->icon('heroicon-o-calculator')
                    ->color('warning')
                    ->visible(fn () => auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('admin'))
                    ->action(function () {
                        // 1. Recalcular subensambles primero (ingredientes para productos de venta)
                        $subrecetas = Producto::where('tipo', 'subensamble')->get();
                        foreach ($subrecetas as $subreceta) {
                            $subreceta->precio_compra = $subreceta->calcularCosto();
                            $subreceta->save();
                        }

                        // 2. Recalcular productos de venta
                        $productosVenta = Producto::where('tipo', 'venta')->get();
                        foreach ($productosVenta as $productoVenta) {
                            $productoVenta->precio_compra = $productoVenta->calcularCosto();
                            $productoVenta->save();
                        }

                        $totalCount = $subrecetas->count() + $productosVenta->count();
                        
                        Notification::make()
                            ->title('Costos Calculados')
                            ->body("Se recalcularon y actualizaron los costos de {$totalCount} recetas (subensambles y venta).")
                            ->success()
                            ->send();
                    })
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
