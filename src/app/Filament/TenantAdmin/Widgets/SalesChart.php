<?php

namespace App\Filament\TenantAdmin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;

class SalesChart extends ChartWidget
{
    protected ?string $heading = 'Weekly order plan';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Number of requests',
                    'data' => [5, 12, 8, 15, 20, 14, 25],
                    'borderColor' => '#3b82f6',
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
            ],
            'labels' => ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}