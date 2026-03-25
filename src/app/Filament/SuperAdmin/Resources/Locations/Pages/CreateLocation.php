<?php

namespace App\Filament\SuperAdmin\Resources\Locations\Pages;

use App\Filament\SuperAdmin\Resources\Locations\LocationResource; // تأكد من هذا السطر!
use Filament\Resources\Pages\CreateRecord;

class CreateLocation extends CreateRecord
{
    protected static string $resource = LocationResource::class;
}