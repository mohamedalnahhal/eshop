<?php

namespace App\Filament\SuperAdmin\Resources\Locations\Pages;

use App\Filament\SuperAdmin\Resources\Locations\LocationResource; // تأكد من هذا السطر!
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLocations extends ListRecords
{
    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}