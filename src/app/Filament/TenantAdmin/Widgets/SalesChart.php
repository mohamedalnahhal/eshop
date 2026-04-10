<?php

namespace App\Filament\TenantAdmin\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SalesChart extends ChartWidget
{
    protected ?string $heading = 'Sales — Last 7 Days';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $start = Carbon::today()->subDays(6)->startOfDay();

        $salesByDay = Order::where('final_price', '>', 0)
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as date, SUM(final_price) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $ordersByDay = Order::where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $days = collect(range(6, 0))->map(fn ($i) => Carbon::today()->subDays($i));
        $labels = $days->map(fn ($d) => $d->format('D'));
        $sales  = $days->map(fn ($d) => (float) ($salesByDay[$d->toDateString()] ?? 0));
        $orders = $days->map(fn ($d) => (int)   ($ordersByDay[$d->toDateString()] ?? 0));

        return [
            'datasets' => [
                [
                    'label'           => __('Sales (₪)'),
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
                'y'  => ['type' => 'linear', 'position' => 'left',  'min' => 0, 'title' => ['display' => true, 'text' => '₪']],
                'y1' => ['type' => 'linear', 'position' => 'right', 'min' => 0, 'grid' => ['drawOnChartArea' => false], 'title' => ['display' => true, 'text' => 'Orders']],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}