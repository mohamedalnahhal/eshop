<?php

namespace App\Filament\TenantAdmin\Resources\Products\Pages;

use App\Filament\TenantAdmin\Resources\Products\ProductResource;
use App\Filament\TenantAdmin\Resources\Products\Widgets\ProductsOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            ProductsOverview::class,
        ];
    }
}
