<?php

namespace App\Filament\TenantAdmin\Widgets;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\TenantUser;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TenantStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $start = Carbon::today()->subDays(6)->startOfDay();

        $dailySales = Order::where('status', OrderStatus::DELIVERED)
            ->where('final_price', '>', 0)
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as date, SUM(final_price) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $dailyOrders = Order::where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $dailyCustomers = TenantUser::where('role', UserRole::CUSTOMER)
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $days = collect(range(6, 0))->map(fn ($i) => Carbon::today()->subDays($i)->toDateString());

        $salesChart    = $days->map(fn ($d) => (float) ($dailySales[$d]    ?? 0))->values()->toArray();
        $ordersChart   = $days->map(fn ($d) => (int)   ($dailyOrders[$d]   ?? 0))->values()->toArray();
        $customersChart = $days->map(fn ($d) => (int)  ($dailyCustomers[$d] ?? 0))->values()->toArray();

        $totalSales = Order::where('status', OrderStatus::DELIVERED)
            ->where('final_price', '>', 0)
            ->sum('final_price');

        $totalOrders = Order::count();

        $totalCustomers = TenantUser::where('role', UserRole::CUSTOMER)->count();

        return [
            Stat::make(__('Total Sales'), number_format($totalSales, 2) . ' ₪')
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