<?php

namespace App\Filament\Widgets;

use App\Models\Inventory\SalesReportImportItem;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseTableWidget;
use Illuminate\Support\Carbon;

class TopProductosTableWidget extends BaseTableWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 4;

    public function getView(): string
    {
        return 'filament.widgets.top-productos-widget';
    }

    protected static ?string $heading = '';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $sedeId = session('sede_id') ?? auth()->user()?->sede_id_actual ?? auth()->user()?->sede_id;

        $startDateRaw = $this->filters['startDate'] ?? null;
        $endDateRaw   = $this->filters['endDate'] ?? null;

        $inicio = $startDateRaw ? Carbon::parse($startDateRaw)->startOfDay() : Carbon::now()->startOfMonth();
        $fin    = $endDateRaw ? Carbon::parse($endDateRaw)->endOfDay() : Carbon::now()->endOfMonth();

        $subQuery = SalesReportImportItem::query()
            ->whereHas('import', function ($q) use ($sedeId, $inicio, $fin) {
                $q->when($sedeId, fn ($q2) => $q2->where('sede_id', $sedeId))
                  ->whereBetween('created_at', [$inicio, $fin]);
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
