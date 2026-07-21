<?php

namespace App\Filament\Widgets;

use App\Models\Inventory\SalesReportImportItem;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseTableWidget;
use Illuminate\Support\Carbon;

class TopProductosTableWidget extends BaseTableWidget
{
    protected static ?int $sort = 4;

    protected static ?string $heading = '🏆 Top 10 Productos Más Consumidos';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $sedeId = session('sede_id') ?? auth()->user()?->sede_id_actual;
        $inicioMesActual = Carbon::now()->startOfMonth();
        $finMesActual    = Carbon::now()->endOfMonth();

        $subQuery = SalesReportImportItem::query()
            ->whereHas('import', function ($q) use ($sedeId, $inicioMesActual, $finMesActual) {
                $q->when($sedeId, fn ($q2) => $q2->where('sede_id', $sedeId))
                  ->whereBetween('created_at', [$inicioMesActual, $finMesActual]);
            })
            ->selectRaw('MIN(id) as id, producto_nombre, SUM(cantidad_venta) as total_cantidad, SUM(venta_neta) as total_venta')
            ->groupBy('producto_nombre');

        $query = SalesReportImportItem::query();
        $query->getModel()->setTable('top_products');
        $query->fromSub($subQuery, 'top_products');

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('producto_nombre')
                    ->label('Nombre del Producto')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('total_cantidad')
                    ->label('Cantidad Vendida')
                    ->numeric(2)
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('total_venta')
                    ->label('Valor Total de Ventas')
                    ->numeric(2)
                    ->prefix('$')
                    ->weight('semibold')
                    ->color('success')
                    ->sortable(),
            ])
            ->defaultSort('total_cantidad', 'desc')
            ->paginated(false);
    }
}
