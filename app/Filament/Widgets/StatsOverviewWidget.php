<?php

namespace App\Filament\Widgets;

use App\Models\Purchase\Compra;
use App\Models\Inventory\Novedad;
use App\Models\Inventory\InventarioSede;
use App\Models\Inventory\SalesReportImportItem;
use App\Models\Inventory\KardexMovimiento;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $sedeId = session('sede_id') ?? auth()->user()?->sede_id_actual;

        // Fechas del mes actual
        $inicioMesActual = Carbon::now()->startOfMonth();
        $finMesActual    = Carbon::now()->endOfMonth();

        // 1. Ventas del Mes (Suma de venta_neta de items importados en el mes actual)
        $ventasMes = SalesReportImportItem::query()
            ->whereHas('import', function ($q) use ($sedeId, $inicioMesActual, $finMesActual) {
                $q->when($sedeId, fn ($q2) => $q2->where('sede_id', $sedeId))
                  ->whereBetween('created_at', [$inicioMesActual, $finMesActual]);
            })
            ->sum('venta_neta');

        // 2. Compras del Mes (Suma de compras aprobadas en el mes actual)
        $comprasMes = Compra::query()
            ->when($sedeId, fn ($q) => $q->deSede($sedeId))
            ->where('status', 'aprobado')
            ->whereBetween('fecha_factura', [$inicioMesActual, $finMesActual])
            ->sum('total');

        // 3. Valor Totalizado de Inventarios (Suma de cantidad_actual * precio_compra de la sede activa)
        $valorInventario = InventarioSede::query()
            ->when($sedeId, fn ($q) => $q->deSede($sedeId))
            ->join('productos', 'inventario_sedes.producto_id', '=', 'productos.id')
            ->whereNull('productos.deleted_at')
            ->selectRaw('SUM(inventario_sedes.cantidad_actual * productos.precio_compra) as total_valor')
            ->value('total_valor') ?? 0;

        // 4. Valor de las Mermas (Suma de novedades/mermas del mes actual)
        $mermasMes = Novedad::query()
            ->when($sedeId, fn ($q) => $q->deSede($sedeId))
            ->whereBetween('created_at', [$inicioMesActual, $finMesActual])
            ->sum('valor_costo');

        // 5. Indicador de Rendimiento / Margen (Meta 25%)
        // Ganancia Neta = Ventas del Mes - Costo de Ventas (tipo_movimiento = salida_venta en Kardex)
        $costoVentas = KardexMovimiento::query()
            ->when($sedeId, fn ($q) => $q->where('sede_id', $sedeId))
            ->where('tipo_movimiento', 'salida_venta')
            ->whereBetween('created_at', [$inicioMesActual, $finMesActual])
            ->sum('costo_total');

        $margen = $ventasMes > 0 ? (($ventasMes - $costoVentas) / $ventasMes) * 100 : 0;
        $colorMargen = $margen >= 25 ? 'success' : 'danger';
        $iconoMargen = $margen >= 25 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';

        return [
            Stat::make('Ventas del Mes', '$' . number_format($ventasMes, 0, ',', '.'))
                ->description('Ventas netas registradas este mes')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->icon('heroicon-o-currency-dollar'),

            Stat::make('Compras del Mes', '$' . number_format($comprasMes, 0, ',', '.'))
                ->description('Compras aprobadas este mes')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info')
                ->icon('heroicon-o-shopping-cart'),

            Stat::make('Valor de Inventario', '$' . number_format($valorInventario, 0, ',', '.'))
                ->description('Existencias totalizadas a precio de costo')
                ->descriptionIcon('heroicon-m-circle-stack')
                ->color('warning')
                ->icon('heroicon-o-archive-box'),

            Stat::make('Valor de las Mermas', '$' . number_format($mermasMes, 0, ',', '.'))
                ->description('Bajas y daños del mes actual')
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
