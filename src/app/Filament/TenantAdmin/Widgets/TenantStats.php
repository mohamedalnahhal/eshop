<?php

namespace App\Filament\TenantAdmin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use App\Models\User;

class TenantStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total orders', Order::count())
                ->description('Orders placed in your store')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            // Stat::make('Registered customers', User::count())
            //     ->description('Number of customers in this store')
            //     ->descriptionIcon('heroicon-m-users')
            //     ->color('primary'),
        ];
    }
}