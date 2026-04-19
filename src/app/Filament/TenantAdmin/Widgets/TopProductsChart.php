<?php

namespace App\Filament\TenantAdmin\Widgets;

use App\Models\OrderItem;
use App\Services\Money\MoneyService;
use App\Services\TenantLocaleService;
use Filament\Widgets\ChartWidget;

class TopProductsChart extends ChartWidget
{
    protected ?string $heading = 'Top 5 Products by Revenue';
    protected static ?int $sort = 4;
    protected bool $hasData = true;

    protected function getData(): array
    {
        // TODO: get sales from payments and account for each payemnt currency

        $moneyService = app(MoneyService::class);

        $results = OrderItem::whereHas('order')
            ->selectRaw("
                COALESCE(product_id, product_name) as product_key,
                MAX(product_id) as product_id,
                MAX(product_name) as product_name,
                SUM(quantity * unit_price) as revenue
            ")
            ->groupBy('product_key')
            ->orderByDesc('revenue')
            ->limit(5)
            ->with('product.translations')
            ->get();

        if ($results->isEmpty()) {
            $this->hasData = false;

            return [
                'datasets' => [['label' => __('Revenue') . ' (' . $moneyService->resolveCurrency() . ')', 'data' => [0]]],
                'labels'   => [__('No data')],
            ];
        }

        $this->hasData = true;

        $localeService = app(TenantLocaleService::class);

        return [
            'datasets' => [
                [
                    'label' => __('Revenue') . ' (' . $moneyService->resolveCurrency() . ')',
                    'data'  => $results->pluck('revenue')->map(fn ($v) => $moneyService->fromMinor($v))->toArray(),
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
            
            'labels' => $results->map(function ($r) use ($localeService) {
                if ($r->product) {
                    return $r->product->name;
                }
                return $localeService->resolveTranslation($r->product_name ?? []) ?: __('Unknown');
            })->toArray(),
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