<?php

namespace App\Filament\Widgets;

use App\Models\Inventory\Novedad;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DistribucionMermasWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'sm'      => 'full',
        'md'      => 1,
        'lg'      => 1,
        'xl'      => 1,
        '2xl'     => 1,
    ];

    // Propiedades de instancia — tal como las define ChartWidget base
    protected ?string $heading = 'Distribución de Mermas del Mes';

    protected ?string $description = 'Dónde se pierde más dinero este mes';

    protected ?string $maxHeight = '300px';

    protected ?string $pollingInterval = '60s';

    protected function getData(): array
    {
        $sedeId = session('sede_id') ?? auth()->user()?->sede_id_actual;

        $inicio = Carbon::now()->startOfMonth();
        $fin    = Carbon::now()->endOfMonth();

        /** @var \Illuminate\Support\Collection $datos */
        $datos = Novedad::query()
            ->when($sedeId, fn ($q) => $q->deSede($sedeId))
            ->whereBetween('created_at', [$inicio, $fin])
            ->selectRaw('tipo, SUM(valor_costo) as total')
            ->groupBy('tipo')
            ->orderByDesc('total')
            ->get();

        // Paleta de colores armoniosa
        $paleta = [
            '#f43f5e', // Rojo rosado
            '#f97316', // Naranja
            '#eab308', // Amarillo
            '#22c55e', // Verde
            '#06b6d4', // Cian
            '#6366f1', // Índigo
            '#a855f7', // Violeta
            '#ec4899', // Rosa
        ];

        $labels  = $datos->pluck('tipo')->map(fn ($t) => ucfirst($t))->toArray();
        $values  = $datos->pluck('total')->map(fn ($v) => round((float) $v, 2))->toArray();
        $colores = array_slice($paleta, 0, count($labels));

        return [
            'datasets' => [
                [
                    'label'           => 'Pérdida ($)',
                    'data'            => $values,
                    'backgroundColor' => $colores,
                    'hoverOffset'     => 8,
                    'borderWidth'     => 2,
                    'borderColor'     => '#1e1e2e',
                ],
            ],
            'labels' => $labels ?: ['Sin datos este mes'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display'  => true,
                    'position' => 'right',
                    'labels'   => [
                        'padding'  => 16,
                        'boxWidth' => 12,
                        'font'     => ['size' => 12],
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'cutout' => '65%',
        ];
    }
}
