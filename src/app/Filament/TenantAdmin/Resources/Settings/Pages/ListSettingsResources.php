<?php

namespace App\Filament\TenantAdmin\Resources\Settings\Pages;

use App\Filament\TenantAdmin\Resources\Settings\SettingResource;
use Filament\Resources\Pages\ListRecords;

class ListSettingsResources extends ListRecords
{
    protected static string $resource = SettingResource::class;
}