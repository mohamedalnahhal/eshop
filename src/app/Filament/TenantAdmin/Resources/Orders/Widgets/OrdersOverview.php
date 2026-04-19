<?php

namespace App\Filament\TenantAdmin\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\Money\MoneyService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrdersOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // TODO: account for each order currency

        $monthlyRevenue = Order::where('status', OrderStatus::DELIVERED)
            ->whereMonth('created_at', now()->month)
            ->sum('total');

        $todayRevenue = Order::where('status', OrderStatus::DELIVERED)
            ->whereDate('created_at', today())
            ->sum('total');

        $avgOrderValue = Order::where('status', OrderStatus::DELIVERED)->avg('total') ?? 0;

        return [
            Stat::make(__('Pending Orders'), Order::where('status', OrderStatus::PENDING)->count())
                ->description(__('Awaiting processing'))
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make(__('Revenue Today'), app(MoneyService::class)->format($todayRevenue))
                ->description(__('This month: ') . app(MoneyService::class)->format($monthlyRevenue))
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make(__('Avg. Order Value'), app(MoneyService::class)->format($avgOrderValue))
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