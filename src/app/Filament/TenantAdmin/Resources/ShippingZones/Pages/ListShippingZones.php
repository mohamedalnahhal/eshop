<?php

namespace App\Filament\TenantAdmin\Resources\ShippingZones\Pages;

use App\Filament\TenantAdmin\Resources\ShippingZones\ShippingZoneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\TenantAdmin\Resources\ShippingZones\Tables\ShippingZonesTable;
use Filament\Tables\Table;

class ListShippingZones extends ListRecords
{
    protected static string $resource = ShippingZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    public function table(Table $table): Table
    {
        return ShippingZonesTable::configure($table);
    }
}
