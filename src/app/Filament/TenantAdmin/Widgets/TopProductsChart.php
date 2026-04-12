<?php

namespace App\Filament\TenantAdmin\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;

class TopProductsChart extends ChartWidget
{
    protected ?string $heading = 'Top 5 Products by Revenue';
    protected static ?int $sort = 4;
    protected bool $hasData = true;

    protected function getData(): array
    {
        $results = OrderItem::whereHas('order')
            ->selectRaw('product_id, SUM(quantity * unit_price) as revenue')
            ->groupBy('product_id')
            ->orderByDesc('revenue')
            ->limit(5)
            ->with('product:id,name')
            ->get();

        if ($results->isEmpty()) {
            $this->hasData = false;

            return [
                'datasets' => [['label' => __('Revenue (₪)'), 'data' => [0]]],
                'labels'   => [__('No data')],
            ];
        }

        $this->hasData = true;

        return [
            'datasets' => [
                [
                    'label' => __('Revenue (₪)'),
                    'data'  => $results->pluck('revenue')->map(fn ($v) => round($v, 2))->toArray(),
                    'backgroundColor' => [
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(245, 158, 11, 0.65)',
                        'rgba(245, 158, 11, 0.5)',
                        'rgba(245, 158, 11, 0.38)',
                        'rgba(245, 158, 11, 0.25)',
                    ],
                    'borderColor' => 'rgba(245, 158, 11, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $results->map(fn ($r) => $r->product?->name ?? __('Unknown'))->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',   // horizontal bars
            'plugins'   => ['legend' => ['display' => false]],
            'scales'    => [
                'x' => ['min' => 0, 'ticks' => ['callback' => '(v) => v + " ₪"']],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}