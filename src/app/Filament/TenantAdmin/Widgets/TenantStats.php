<?php

namespace App\Filament\TenantAdmin\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Customer;
use App\Services\Money\MoneyService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TenantStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // TODO: get sales from payments and account for each payemnt currency
        
        $start = Carbon::today()->subDays(6)->startOfDay();

        $dailySales = Order::where('status', OrderStatus::DELIVERED)
            ->where('total', '>', 0)
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $dailyOrders = Order::where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $dailyCustomers = Customer::where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $days = collect(range(6, 0))->map(fn ($i) => Carbon::today()->subDays($i)->toDateString());

        $salesChart    = $days->map(fn ($d) => (float) ($dailySales[$d]    ?? 0))->values()->toArray();
        $ordersChart   = $days->map(fn ($d) => (int)   ($dailyOrders[$d]   ?? 0))->values()->toArray();
        $customersChart = $days->map(fn ($d) => (int)  ($dailyCustomers[$d] ?? 0))->values()->toArray();

        $totalSales = Order::where('status', OrderStatus::DELIVERED)
            ->where('total', '>', 0)
            ->sum('total');

        $totalOrders = Order::count();

        $totalCustomers = Customer::count();

        return [
            Stat::make(__('Total Sales'), app(MoneyService::class)->format($totalSales))
                ->description(__('Delivered orders only'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart($salesChart),

            Stat::make(__('Total Orders'), $totalOrders)
                ->description(__('All orders in the store'))
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary')
                ->chart($ordersChart),

            Stat::make(__('Customers'), $totalCustomers)
                ->description(__('Registered customers'))
                ->descriptionIcon('heroicon-m-users')
                ->color('info')
                ->chart($customersChart),
        ];
    }
}