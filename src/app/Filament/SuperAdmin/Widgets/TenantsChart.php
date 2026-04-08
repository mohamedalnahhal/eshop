<?php

namespace App\Filament\SuperAdmin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Tenant;
use Illuminate\Support\Carbon;

class TenantsChart extends ChartWidget
{
    protected ?string $heading = "New Stores This Year";

    protected function getData(): array
    {
        $months = collect(range(1, 12))->map(fn($m) => Carbon::create(now()->year, $m, 1));

        $data = $months->map(fn($month) =>
            Tenant::whereYear("created_at", $month->year)
                ->whereMonth("created_at", $month->month)
                ->count()
        );

        $labels = $months->map(fn($month) => $month->format("M"));

        return [
            "datasets" => [
                [
                    "label" => "New Stores",
                    "data" => $data->values()->toArray(),
                    "borderColor" => "#3b82f6",
                    "backgroundColor" => "rgba(59, 130, 246, 0.1)",
                    "fill" => "start",
                ],
            ],
            "labels" => $labels->values()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return "line";
    }
}
