<?php

namespace App\Filament\Widgets;

use App\Models\Purchase\Compra;
use App\Models\Inventory\Novedad;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class TendenciaComprasMermasWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'sm'      => 'full',
        'md'      => 1,
        'lg'      => 1,
        'xl'      => 1,
        '2xl'     => 1,
    ];

    // Propiedades de instancia (no estáticas) — tal como las define ChartWidget base
    protected ?string $heading = 'Tendencia: Compras vs Mermas';

    protected ?string $description = 'Comparativo del período seleccionado';

    protected ?string $maxHeight = '300px';

    protected ?string $pollingInterval = '60s';

    protected function getData(): array
    {
        $sedeId = session('sede_id') ?? auth()->user()?->sede_id_actual ?? auth()->user()?->sede_id;

        $startDateRaw = $this->filters['startDate'] ?? null;
        $endDateRaw   = $this->filters['endDate'] ?? null;

        $meses   = [];
        $compras = [];
        $mermas  = [];

        if ($startDateRaw && $endDateRaw) {
            $start = Carbon::parse($startDateRaw)->startOfDay();
            $end   = Carbon::parse($endDateRaw)->endOfDay();
            $diffDays = $start->diffInDays($end);

            if ($diffDays <= 31) {
                $period = \Carbon\CarbonPeriod::create($start, $end);
                foreach ($period as $date) {
                    $dayStart = $date->copy()->startOfDay();
                    $dayEnd   = $date->copy()->endOfDay();

                    $meses[] = $date->format('d/m');

                    $compras[] = (float) Compra::query()
                        ->when($sedeId, fn ($q) => $q->deSede($sedeId))
                        ->where('status', 'aprobado')
                        ->whereBetween('fecha_factura', [$dayStart, $dayEnd])
                        ->sum('total');

                    $mermas[] = (float) Novedad::query()
                        ->when($sedeId, fn ($q) => $q->deSede($sedeId))
                        ->whereBetween('created_at', [$dayStart, $dayEnd])
                        ->sum('valor_costo');
                }
            } else {
                $period = \Carbon\CarbonPeriod::create($start->copy()->startOfMonth(), '1 month', $end->copy()->startOfMonth());
                foreach ($period as $date) {
                    $monthStart = $date->copy()->startOfMonth();
                    $monthEnd   = $date->copy()->endOfMonth();

                    $meses[] = ucfirst($date->translatedFormat('M Y'));

                    $compras[] = (float) Compra::query()
                        ->when($sedeId, fn ($q) => $q->deSede($sedeId))
                        ->where('status', 'aprobado')
                        ->whereBetween('fecha_factura', [$monthStart, $monthEnd])
                        ->sum('total');

                    $mermas[] = (float) Novedad::query()
                        ->when($sedeId, fn ($q) => $q->deSede($sedeId))
                        ->whereBetween('created_at', [$monthStart, $monthEnd])
                        ->sum('valor_costo');
                }
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $mes    = Carbon::now()->subMonths($i);
                $inicio = $mes->copy()->startOfMonth();
                $fin    = $mes->copy()->endOfMonth();

                $meses[] = ucfirst($mes->translatedFormat('M Y'));

                $compras[] = (float) Compra::query()
                    ->when($sedeId, fn ($q) => $q->deSede($sedeId))
                    ->where('status', 'aprobado')
                    ->whereBetween('fecha_factura', [$inicio, $fin])
                    ->sum('total');

                $mermas[] = (float) Novedad::query()
                    ->when($sedeId, fn ($q) => $q->deSede($sedeId))
                    ->whereBetween('created_at', [$inicio, $fin])
                    ->sum('valor_costo');
            }
        }

        return [
            'datasets' => [
                [
                    'label'                => 'Compras Aprobadas ($)',
                    'data'                 => $compras,
                    'borderColor'          => '#6366f1',
                    'backgroundColor'      => 'rgba(99,102,241,0.1)',
                    'fill'                 => true,
                    'tension'              => 0.4,
                    'pointBackgroundColor' => '#6366f1',
                    'pointRadius'          => 5,
                ],
                [
                    'label'                => 'Mermas / Novedades ($)',
                    'data'                 => $mermas,
                    'borderColor'          => '#f43f5e',
                    'backgroundColor'      => 'rgba(244,63,94,0.1)',
                    'fill'                 => true,
                    'tension'              => 0.4,
                    'pointBackgroundColor' => '#f43f5e',
                    'pointRadius'          => 5,
                ],
            ],
            'labels' => $meses,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display'  => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid'        => ['color' => 'rgba(156,163,175,0.1)'],
                ],
                'x' => [
                    'grid' => ['color' => 'rgba(156,163,175,0.1)'],
                ],
            ],
            'interaction' => [
                'mode'      => 'index',
                'intersect' => false,
            ],
        ];
    }
}
