<?php

namespace App\Filament\TenantAdmin\Widgets;

use App\Models\Order;
use App\Services\Money\MoneyService;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SalesChart extends ChartWidget
{
    // TODO: get sales from payments and account for each payemnt currency

    protected ?string $heading = 'Sales — Last 7 Days';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $moneyService = app(MoneyService::class);
        $start = Carbon::today()->subDays(6)->startOfDay();

        $salesByDay = Order::where('total', '>', 0)
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $ordersByDay = Order::where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $days = collect(range(6, 0))->map(fn ($i) => Carbon::today()->subDays($i));
        $labels = $days->map(fn ($d) => $d->format('D'));
        $sales  = $days->map(fn ($d) => (float) $moneyService->format($salesByDay[$d->toDateString()] ?? 0));
        $orders = $days->map(fn ($d) => (int)   ($ordersByDay[$d->toDateString()] ?? 0));

        return [
            'datasets' => [
                [
                    'label'           => __('Sales') . ' (' . $moneyService->resolveCurrency() . ')',
                    'data'            => $sales->values()->toArray(),
                    'borderColor'     => '#3b82f6',
                    'fill'            => true,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'yAxisID'         => 'y',
                ],
                [
                    'label'           => __('Orders'),
                    'data'            => $orders->values()->toArray(),
                    'borderColor'     => '#10b981',
                    'fill'            => true,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'yAxisID'         => 'y1',
                ],
            ],
            'labels' => $labels->values()->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y'  => ['type' => 'linear', 'position' => 'left',  'min' => 0, 'title' => ['display' => true, 'text' => ' (' . app(MoneyService::class)->resolveCurrency() . ')']],
                'y1' => ['type' => 'linear', 'position' => 'right', 'min' => 0, 'grid' => ['drawOnChartArea' => false], 'title' => ['display' => true, 'text' => 'Orders']],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}