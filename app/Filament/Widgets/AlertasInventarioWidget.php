<?php

namespace App\Filament\Widgets;

use App\Models\Inventory\InventarioSede;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class AlertasInventarioWidget extends BaseWidget
{
    protected static ?string $heading = '⚠️ Productos en Punto de Reorden';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected static string $pollingInterval = '60s';

    protected static ?int $defaultPaginationPageOption = 8;

    public function table(Table $table): Table
    {
        $sedeId = session('sede_id') ?? auth()->user()?->sede_id_actual;

        return $table
            ->query(
                InventarioSede::query()
                    ->with('producto')
                    ->when($sedeId, fn (Builder $q) => $q->deSede($sedeId))
                    ->enPuntoDeReorden()
                    ->orderBy('cantidad_actual')
            )
            ->columns([
                Tables\Columns\TextColumn::make('producto.nombre')
                    ->label('Producto')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-cube'),

                Tables\Columns\TextColumn::make('cantidad_actual')
                    ->label('Stock Actual')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => $record->cantidad_actual <= $record->stock_minimo
                        ? 'danger'
                        : 'warning'
                    ),

                Tables\Columns\TextColumn::make('stock_minimo')
                    ->label('Stock Mínimo')
                    ->numeric(decimalPlaces: 2)
                    ->color('gray'),

                Tables\Columns\TextColumn::make('punto_reorden')
                    ->label('Punto de Reorden')
                    ->numeric(decimalPlaces: 2)
                    ->color('gray'),

                Tables\Columns\TextColumn::make('stock_maximo')
                    ->label('Stock Máximo')
                    ->numeric(decimalPlaces: 2)
                    ->color('gray'),

                Tables\Columns\TextColumn::make('sugerido_compra')
                    ->label('Sugerido a Comprar')
                    ->state(fn ($record) => $record->sugerido_compra)
                    ->numeric(decimalPlaces: 2)
                    ->sortable(false)
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-o-shopping-cart'),

                Tables\Columns\TextColumn::make('producto.unidadMedida.abreviatura')
                    ->label('Unidad')
                    ->badge()
                    ->color('info'),
            ])
            ->striped()
            ->paginated([8, 15, 25])
            ->defaultPaginationPageOption(8)
            ->emptyStateHeading('¡Todo en orden!')
            ->emptyStateDescription('Ningún producto ha alcanzado su punto de reorden.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
