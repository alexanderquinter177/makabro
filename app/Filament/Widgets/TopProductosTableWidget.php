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

    protected string $view = 'filament.widgets.top-productos-widget';

    protected static ?string $heading = '';

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
        $query->fromSub($subQuery, 'top_products')
            ->orderBy('total_cantidad', 'desc')
            ->take(10);

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('producto_nombre')
                    ->label('Producto')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('total_cantidad')
                    ->label('Vendido')
                    ->numeric(2)
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('total_venta')
                    ->label('Valor ($)')
                    ->numeric(2)
                    ->prefix('$')
                    ->weight('semibold')
                    ->color('success')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('total_cantidad', 'desc')
            ->paginated(false);
    }
}
