<?php

namespace App\Filament\SuperAdmin\Widgets;

use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use App\Models\Product;
use App\Models\Tenant;

class TenantStats extends BaseWidget
{
    public ?Tenant $tenant = null;

protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $tenantId = $this->tenant?->id;

        $totalSales = Order::where("tenant_id", $tenantId)
            ->where("status", "completed")
            ->where("total", ">", 0)
            ->sum("total");

        $totalOrders = Order::where("tenant_id", $tenantId)->count();

        $totalProducts = Product::where("tenant_id", $tenantId)->count();

        $totalCustomers = Customer::where("tenant_id", $tenantId)->count();

        return [
            Stat::make("Total Sales", number_format($totalSales, 2) . " ₪")
                ->description("Total Completed Sales")
                ->descriptionIcon("heroicon-m-banknotes")
                ->color("success")
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make("Total Orders", $totalOrders)
                ->description("Total Orders In The Store")
                ->descriptionIcon("heroicon-m-shopping-bag")
                ->color("primary")
                ->chart([3, 7, 5, 12, 8, 15, 20]),

            Stat::make("Number of Products", $totalProducts)
                ->description("Products In The Store")
                ->descriptionIcon("heroicon-m-cube")
                ->color("warning")
                ->chart([10, 8, 12, 9, 14, 11, 15]),

            Stat::make("Number of Customers", $totalCustomers)
                ->description("Customers Registered In The Store")
                ->descriptionIcon("heroicon-m-users")
                ->color("info")
                ->chart([2, 5, 3, 8, 6, 10, 9]),
        ];
    }
}
