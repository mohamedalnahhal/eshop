<?php

namespace App\Filament\TenantAdmin\Resources\Categories\Pages;

use App\Filament\TenantAdmin\Resources\Categories\CategoryResource;
use App\Models\Category;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Contracts\Tenant;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}
