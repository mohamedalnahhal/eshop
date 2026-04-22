<?php

namespace App\Filament\TenantAdmin\Resources\ShippingZones\Pages;

use App\Filament\TenantAdmin\Resources\ShippingZones\ShippingZoneResource;
use Filament\Resources\Pages\CreateRecord;

class CreateShippingZone extends CreateRecord
{
    protected static string $resource = ShippingZoneResource::class;
}
