<?php

namespace App\Filament\Widgets;

use App\Models\Purchase\Compra;
use App\Models\Inventory\Novedad;
use App\Models\Inventory\InventarioSede;
use App\Models\Inventory\SalesReportImportItem;
use App\Models\Inventory\KardexMovimiento;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $sedeId = session('sede_id') ?? auth()->user()?->sede_id_actual;

        // Fechas del filtro o del mes actual por defecto
        $startDateRaw = $this->filters['startDate'] ?? null;
        $endDateRaw   = $this->filters['endDate'] ?? null;

        $inicio = $startDateRaw ? Carbon::parse($startDateRaw)->startOfDay() : Carbon::now()->startOfMonth();
        $fin    = $endDateRaw ? Carbon::parse($endDateRaw)->endOfDay() : Carbon::now()->endOfMonth();

        // 1. Ventas del Período
        $ventasMes = SalesReportImportItem::query()
            ->whereHas('import', function ($q) use ($sedeId, $inicio, $fin) {
                $q->when($sedeId, fn ($q2) => $q2->where('sede_id', $sedeId))
                  ->whereBetween('created_at', [$inicio, $fin]);
            })
            ->sum('venta_neta');

        // 2. Compras del Período (Suma de compras aprobadas en el rango seleccionado)
        $comprasMes = Compra::query()
            ->when($sedeId, fn ($q) => $q->deSede($sedeId))
            ->where('status', 'aprobado')
            ->whereBetween('fecha_factura', [$inicio, $fin])
            ->sum('total');

        // 3. Valor Totalizado de Inventarios (Existencias actuales a precio de costo)
        $valorInventario = InventarioSede::query()
            ->when($sedeId, fn ($q) => $q->deSede($sedeId))
            ->join('productos', 'inventario_sedes.producto_id', '=', 'productos.id')
            ->whereNull('productos.deleted_at')
            ->selectRaw('SUM(inventario_sedes.cantidad_actual * productos.precio_compra) as total_valor')
            ->value('total_valor') ?? 0;

        // 4. Valor de las Mermas (Suma de novedades/mermas en el rango)
        $mermasMes = Novedad::query()
            ->when($sedeId, fn ($q) => $q->deSede($sedeId))
            ->whereBetween('created_at', [$inicio, $fin])
            ->sum('valor_costo');

        // 5. Indicador de Rendimiento / Margen (Meta 25%)
        $costoVentas = KardexMovimiento::query()
            ->when($sedeId, fn ($q) => $q->where('sede_id', $sedeId))
            ->where('tipo_movimiento', 'salida_venta')
            ->whereBetween('created_at', [$inicio, $fin])
            ->sum('costo_total');

        $margen = $ventasMes > 0 ? (($ventasMes - $costoVentas) / $ventasMes) * 100 : 0;
        $colorMargen = $margen >= 25 ? 'success' : 'danger';
        $iconoMargen = $margen >= 25 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';

        return [
            Stat::make('Ventas del Período', '$' . number_format($ventasMes, 0, ',', '.'))
                ->description('Ventas netas en el rango seleccionado')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->icon('heroicon-o-currency-dollar'),

            Stat::make('Compras del Período', '$' . number_format($comprasMes, 0, ',', '.'))
                ->description('Compras aprobadas en el rango seleccionado')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info')
                ->icon('heroicon-o-shopping-cart'),

            Stat::make('Valor de Inventario', '$' . number_format($valorInventario, 0, ',', '.'))
                ->description('Existencias totalizadas a precio de costo')
                ->descriptionIcon('heroicon-m-circle-stack')
                ->color('warning')
                ->icon('heroicon-o-archive-box'),

            Stat::make('Valor de las Mermas', '$' . number_format($mermasMes, 0, ',', '.'))
                ->description('Bajas y daños en el rango seleccionado')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($mermasMes > 500000 ? 'danger' : 'warning')
                ->icon('heroicon-o-trash'),

            Stat::make('Margen de Rendimiento', number_format($margen, 2, ',', '.') . '%')
                ->description($margen >= 25 ? 'Meta alcanzada (>= 25%)' : 'Por debajo de la meta (< 25%)')
                ->descriptionIcon($iconoMargen)
                ->color($colorMargen)
                ->icon('heroicon-o-presentation-chart-line'),
        ];
    }
}
