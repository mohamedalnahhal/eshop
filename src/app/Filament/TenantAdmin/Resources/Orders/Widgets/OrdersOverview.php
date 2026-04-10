<?php

namespace App\Filament\TenantAdmin\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrdersOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $monthlyRevenue = Order::where('status', OrderStatus::DELIVERED)
            ->whereMonth('created_at', now()->month)
            ->sum('final_price');

        $todayRevenue = Order::where('status', OrderStatus::DELIVERED)
            ->whereDate('created_at', today())
            ->sum('final_price');

        $avgOrderValue = Order::where('status', OrderStatus::DELIVERED)->avg('final_price') ?? 0;

        return [
            Stat::make(__('Pending Orders'), Order::where('status', OrderStatus::PENDING)->count())
                ->description(__('Awaiting processing'))
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make(__('Revenue Today'), number_format($todayRevenue, 2) . ' ' . config('app.currency', '₪'))
                ->description(__('This month: ') . number_format($monthlyRevenue, 2))
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make(__('Avg. Order Value'), number_format($avgOrderValue, 2) . ' ' . config('app.currency', '₪'))
                ->description(__('Completed orders only'))
                ->descriptionIcon('heroicon-m-calculator')
                ->color('info'),

            Stat::make(__('Total Orders'), Order::count())
                ->description(
                    __(':count completed', ['count' => Order::where('status', OrderStatus::DELIVERED)->count()])
                )
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('gray'),
        ];
    }
}