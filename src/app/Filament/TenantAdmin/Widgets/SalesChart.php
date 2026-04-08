<?php

namespace App\Filament\TenantAdmin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Illuminate\Support\Carbon;

class SalesChart extends ChartWidget
{
    protected ?string $heading = "المبيعات - آخر 7 أيام";
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $tenantId = tenant("id");
        $days = collect(range(6, 0))->map(fn($i) => Carbon::today()->subDays($i));

        $sales = $days->map(fn($day) =>
            Order::where("tenant_id", $tenantId)
                ->whereDate("created_at", $day)
                ->where("final_price", ">", 0)
                ->sum("final_price")
        );

        $orders = $days->map(fn($day) =>
            Order::where("tenant_id", $tenantId)
                ->whereDate("created_at", $day)
                ->count()
        );

        $labels = $days->map(fn($day) => $day->format("D"));

        return [
            "datasets" => [
                [
                    "label" => "المبيعات",
                    "data" => $sales->values()->toArray(),
                    "borderColor" => "#3b82f6",
                    "fill" => true,
                    "backgroundColor" => "rgba(59, 130, 246, 0.1)",
                ],
                [
                    "label" => "عدد الطلبات",
                    "data" => $orders->values()->toArray(),
                    "borderColor" => "#10b981",
                    "fill" => true,
                    "backgroundColor" => "rgba(16, 185, 129, 0.1)",
                ],
            ],
            "labels" => $labels->values()->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            "scales" => [
                "y" => [
                    "min" => 0,
                    "ticks" => [
                        "stepSize" => 1,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return "line";
    }
}
