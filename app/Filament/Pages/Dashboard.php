<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AlertasInventarioWidget;
use App\Filament\Widgets\DistribucionMermasWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\TendenciaComprasMermasWidget;
use App\Filament\Widgets\TopProductosTableWidget;
use App\Filament\Widgets\CambiosPrecioTableWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Centro de Control';

    protected static ?int $navigationSort = -1;

    /**
     * Define la cuadrícula del dashboard.
     *
     * 2 columnas → los gráficos se dividen 1|1.
     * Los widgets con columnSpan='full' seguirán ocupando todo el ancho.
     */
    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'sm'      => 2,
            'md'      => 2,
            'lg'      => 2,
            'xl'      => 2,
            '2xl'     => 2,
        ];
    }

    /**
     * Orden de widgets en el dashboard:
     *  1. StatsOverviewWidget       → full-width (5 tarjetas de KPIs)
     *  2. TendenciaComprasMermasWidget → col 1 de 2 (gráfico líneas)
     *  3. DistribucionMermasWidget  → col 2 de 2 (gráfico dona)
     *  4. AlertasInventarioWidget   → full-width (tabla de alertas)
     *  5. TopProductosTableWidget   → full-width (tabla de consumos)
     *  6. CambiosPrecioTableWidget  → full-width (tabla de precios)
     */
    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            TendenciaComprasMermasWidget::class,
            DistribucionMermasWidget::class,
            AlertasInventarioWidget::class,
            TopProductosTableWidget::class,
            CambiosPrecioTableWidget::class,
        ];
    }
}
