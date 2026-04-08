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
        Stat::make('Total Tenants', Tenant::count())
            ->description('Stores Registered In The System')
            ->descriptionIcon('heroicon-m-users')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('success'),
            
        Stat::make('New subscribers', Tenant::where('created_at', '>=', now()->subDays(30))->count())
            ->description('Last 30 days')
            ->color('info'),
    ];
}   
}
