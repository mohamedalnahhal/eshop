<?php

namespace App\Filament\SuperAdmin\Resources\Tenants\Pages;

use App\Filament\SuperAdmin\Resources\Tenants\TenantResource;
use Filament\Resources\Pages\Page;
use App\Filament\SuperAdmin\Widgets\TenantStats;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class ViewTenant extends Page
{
    use InteractsWithRecord;

    protected static string $resource = TenantResource::class;

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
}
