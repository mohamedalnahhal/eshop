<?php

namespace App\Filament\SuperAdmin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    public function getStats(): array
{
    return [
        \Filament\Widgets\StatsOverviewWidget\Stat::make('Total Tenants', \App\Models\Tenant::count())
            ->description('Stores registered in the system')
            ->descriptionIcon('heroicon-m-users')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('success'),
            
        \Filament\Widgets\StatsOverviewWidget\Stat::make('New subscribers', \App\Models\Tenant::where('created_at', '>=', now()->subDays(30))->count())
            ->description('Last 30 days')
            ->color('info'),
    ];
}   
}
