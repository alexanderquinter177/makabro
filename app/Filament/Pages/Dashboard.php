<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AlertasInventarioWidget;
use App\Filament\Widgets\DistribucionMermasWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\TendenciaComprasMermasWidget;
use App\Filament\Widgets\TopProductosTableWidget;
use App\Filament\Widgets\CambiosPrecioTableWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFilters;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Carbon;

class Dashboard extends BaseDashboard
{
    use HasFilters;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Centro de Control';

    protected static ?int $navigationSort = -1;

    public function mount(): void
    {
        if (empty($this->filters)) {
            $this->filters = [
                'preset'    => 'este_mes',
                'startDate' => Carbon::now()->startOfMonth()->format('Y-m-d'),
                'endDate'   => Carbon::now()->endOfMonth()->format('Y-m-d'),
            ];
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('filtrarFechas')
                ->label(function () {
                    $start = !empty($this->filters['startDate']) 
                        ? Carbon::parse($this->filters['startDate'])->format('d/m/Y') 
                        : 'Inicio';
                    $end = !empty($this->filters['endDate']) 
                        ? Carbon::parse($this->filters['endDate'])->format('d/m/Y') 
                        : 'Fin';

                    return "📅 Fechas: {$start} - {$end}";
                })
                ->icon('heroicon-o-funnel')
                ->color('primary')
                ->modalHeading('Filtrar Estadísticas del Dashboard')
                ->modalDescription('Seleccione el rango de fechas para consultar compras, ventas y mermas.')
                ->modalSubmitActionLabel('Aplicar Filtro')
                ->modalIcon('heroicon-o-calendar')
                ->modalWidth('md')
                ->fillForm(fn () => [
                    'preset'    => $this->filters['preset'] ?? 'este_mes',
                    'startDate' => $this->filters['startDate'] ?? Carbon::now()->startOfMonth()->format('Y-m-d'),
                    'endDate'   => $this->filters['endDate'] ?? Carbon::now()->endOfMonth()->format('Y-m-d'),
                ])
                ->form([
                    Select::make('preset')
                        ->label('Período Rápido')
                        ->options([
                            'este_mes'     => '📅 Este Mes',
                            'mes_anterior' => '⏪ Mes Anterior',
                            'ultimos_7'    => '⚡ Últimos 7 Días',
                            'ultimos_30'   => '📆 Últimos 30 Días',
                            'custom'       => '✏️ Personalizado',
                        ])
                        ->default('este_mes')
                        ->prefixIcon('heroicon-o-funnel')
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state === 'este_mes') {
                                $set('startDate', Carbon::now()->startOfMonth()->format('Y-m-d'));
                                $set('endDate', Carbon::now()->endOfMonth()->format('Y-m-d'));
                            } elseif ($state === 'mes_anterior') {
                                $set('startDate', Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'));
                                $set('endDate', Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d'));
                            } elseif ($state === 'ultimos_7') {
                                $set('startDate', Carbon::now()->subDays(6)->format('Y-m-d'));
                                $set('endDate', Carbon::now()->format('Y-m-d'));
                            } elseif ($state === 'ultimos_30') {
                                $set('startDate', Carbon::now()->subDays(29)->format('Y-m-d'));
                                $set('endDate', Carbon::now()->format('Y-m-d'));
                            }
                        }),

                    Grid::make(2)
                        ->schema([
                            DatePicker::make('startDate')
                                ->label('Fecha Desde')
                                ->required()
                                ->displayFormat('d/m/Y')
                                ->prefixIcon('heroicon-o-calendar')
                                ->live()
                                ->afterStateUpdated(fn (callable $set) => $set('preset', 'custom')),

                            DatePicker::make('endDate')
                                ->label('Fecha Hasta')
                                ->required()
                                ->displayFormat('d/m/Y')
                                ->prefixIcon('heroicon-o-calendar')
                                ->live()
                                ->afterStateUpdated(fn (callable $set) => $set('preset', 'custom')),
                        ]),
                ])
                ->action(function (array $data) {
                    $this->filters = $data;
                    $this->updatedFilters();
                }),
        ];
    }

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
