<?php

namespace App\Filament\TenantAdmin\Resources\Products\Pages;

use App\Filament\TenantAdmin\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}
