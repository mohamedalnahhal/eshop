<?php

namespace App\Filament\TenantAdmin\Resources\Suppliers\Pages;

use App\Filament\TenantAdmin\Resources\Suppliers\SupplierResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSupplier extends CreateRecord
{
    protected static string $resource = SupplierResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['tenant_id'] = auth()->user()->tenants()->first()->id;
        
        return $data;
    }
}
