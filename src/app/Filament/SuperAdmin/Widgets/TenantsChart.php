<?php

namespace App\Filament\SuperAdmin\Widgets;
use Flowframe\Trend\Trend;
use Filament\Widgets\ChartWidget;

class TenantsChart extends ChartWidget
{
    protected ?string $heading = 'Tenants Chart';

    protected function getData(): array
{
    $data = \Flowframe\Trend\Trend::model(\App\Models\Tenant::class)
        ->between(start: now()->startOfYear(), end: now()->endOfYear())
        ->perMonth()
        ->count();

    return [
        'datasets' => [
            [
                'label' => 'New Stores',
                'data' => $data->map(fn ($value) => $value->aggregate),
                'borderColor' => '#3b82f6',
            ],
        ],
        'labels' => $data->map(fn ($value) => $value->date),
    ];
}

    protected function getType(): string
    {
        return 'line';
    }
}
