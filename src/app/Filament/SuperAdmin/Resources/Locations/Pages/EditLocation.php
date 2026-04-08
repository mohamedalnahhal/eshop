<?php

namespace App\Filament\SuperAdmin\Resources\Locations\Pages;

use App\Filament\SuperAdmin\Resources\Locations\LocationResource; // تأكد من هذا السطر!
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLocation extends EditRecord
{
    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}