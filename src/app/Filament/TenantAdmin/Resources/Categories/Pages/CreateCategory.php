<?php

namespace App\Filament\TenantAdmin\Resources\Categories\Pages;

use App\Filament\TenantAdmin\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}
