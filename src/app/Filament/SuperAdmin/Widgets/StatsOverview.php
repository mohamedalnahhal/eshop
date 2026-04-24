<?php

namespace App\Filament\SuperAdmin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Tenant;

class StatsOverview extends StatsOverviewWidget
{
    public function getStats(): array
{
    return [
        Stat::make('Total Shops', Tenant::count())
            ->description('Shops Registered In The System')
            ->descriptionIcon('heroicon-m-users')
            ->color('success'),
            
        Stat::make('New Subscribers', Tenant::where('created_at', '>=', now()->subDays(30))->count())
            ->description('Last 30 days')
            ->color('info'),
    ];
}   
}
