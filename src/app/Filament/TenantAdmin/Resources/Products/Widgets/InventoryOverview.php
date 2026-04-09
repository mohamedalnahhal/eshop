<?php

namespace App\Filament\TenantAdmin\Resources\Products\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InventoryOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__('Total Products'), Product::count())
                ->description(__('All items in your store'))
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),

            Stat::make(__('Low Stock'), Product::whereBetween('stock', [1, 10])->count())
                ->description(__('Items needing restock'))
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('warning'),

            Stat::make(__('Out of Stock'), Product::where('stock', '<=', 0)->count())
                ->description(__('Urgent attention needed'))
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make(__('Value of Stock'), function () {
                $totalValue = Product::sum(\DB::raw('stock * price'));
                return '$' . number_format($totalValue, 2);
            })
                ->description(__('Total assets value'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}