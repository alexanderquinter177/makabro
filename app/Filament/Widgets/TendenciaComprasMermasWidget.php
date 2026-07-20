<?php

namespace App\Filament\Widgets;

use App\Models\Purchase\Compra;
use App\Models\Inventory\Novedad;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TendenciaComprasMermasWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 1;

    // Propiedades de instancia (no estáticas) — tal como las define ChartWidget base
    protected ?string $heading = 'Tendencia: Compras vs Mermas';

    protected ?string $description = 'Comparativo de los últimos 6 meses';

    protected ?string $maxHeight = '300px';

    protected ?string $pollingInterval = '60s';

    protected function getData(): array
    {
        $sedeId = session('sede_id') ?? auth()->user()?->sede_id_actual;

        $meses   = [];
        $compras = [];
        $mermas  = [];

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
