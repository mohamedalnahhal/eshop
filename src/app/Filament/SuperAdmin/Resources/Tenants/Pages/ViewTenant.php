<?php

namespace App\Filament\SuperAdmin\Resources\Tenants\Pages;

use App\Filament\SuperAdmin\Resources\Tenants\TenantResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\TenantAdmin\Widgets\TenantStats;
use App\Models\Tenant;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class ViewTenant extends Page
{
    use InteractsWithRecord;

    protected static string $resource = TenantResource::class;

    protected string $view = 'filament.tenant.pages.tenant-details';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TenantStats::make([
                'tenant' => $this->record,
            ]),
        ];
    }

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //          TenantStats::make([
    //         'tenant' => $this->record,
    //     ]),

    //     ];
    // }

    // protected function getTotalSales(): string
    // {
    //     return number_format(
    //         $this->record->orders()->where('status', 'completed')->sum('total_price'),
    //         2
    //     ) . ' ₪';
    // }

    // protected function getTotalOrders(): int
    // {
    //     return $this->record->orders()->count();
    // }

    // protected function getProductsCount(): int
    // {
    //     return $this->record->products()->count();
    // }

    // protected function getCustomersCount(): int
    // {
    //     return $this->record->users()->count();
    // }
}
