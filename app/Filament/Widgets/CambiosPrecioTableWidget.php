<?php

namespace App\Filament\Widgets;

use App\Models\Inventory\ProductPriceHistory;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseTableWidget;
use Illuminate\Database\Eloquent\Builder;

class CambiosPrecioTableWidget extends BaseTableWidget
{
    protected static ?int $sort = 5;

    protected static ?string $heading = '🔄 Últimos Cambios de Precio de Compra';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $sedeId = session('sede_id') ?? auth()->user()?->sede_id_actual;

        return $table
            ->query(
                ProductPriceHistory::query()
                    ->whereHas('compra', function ($q) use ($sedeId) {
                        $q->when($sedeId, fn ($q2) => $q2->where('sede_id', $sedeId));
                    })
                    ->with(['producto', 'proveedor'])
            )
            ->columns([
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->icon('heroicon-o-clock')
                    ->color('gray'),

                TextColumn::make('producto.nombre')
                    ->label('Producto')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('precio_nuevo')
                    ->label('Precio Nuevo')
                    ->numeric(2)
                    ->prefix('$')
                    ->weight('semibold'),

                TextColumn::make('diferencia')
                    ->label('Diferencia')
                    ->state(fn ($record) => (float) $record->precio_nuevo - (float) $record->precio_anterior)
                    ->numeric(2)
                    ->prefix('$')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success')
                    ->icon(fn ($state) => $state > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down'),

                TextColumn::make('proveedor.nombre')
                    ->label('Proveedor')
                    ->searchable()
                    ->default('—')
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('precio_anterior')
                    ->label('Precio Ant.')
                    ->numeric(2)
                    ->prefix('$')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('variacion')
                    ->label('Variación')
                    ->state(fn ($record) => $record->variacion_porcentual)
                    ->suffix('%')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([8, 15])
            ->defaultPaginationPageOption(8);
    }
}
