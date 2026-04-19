<?php

namespace App\Filament\TenantAdmin\Resources\Customers\Pages;

use App\Filament\TenantAdmin\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}