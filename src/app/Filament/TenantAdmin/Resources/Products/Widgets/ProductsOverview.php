<?php

namespace App\Filament\TenantAdmin\Resources\Products\Widgets;

use App\Models\Product;
use App\Services\Money\MoneyService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__('Total Products'), Product::count())
                ->description(__('All products in your catalog'))
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),

            Stat::make(__('Avg. Price'), app(MoneyService::class)->format(Product::avg('price')))
                ->description(__('Across all products'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('gray'),
        ];
    }
}