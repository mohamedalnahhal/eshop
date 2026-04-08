<?php

namespace App\Filament\TenantAdmin\Resources\Settings;

use App\Models\TenantSetting;
use App\Filament\TenantAdmin\Resources\Settings\Schemas\SettingsForm;
use App\Filament\TenantAdmin\Resources\Settings\Pages\ManageSettings;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class SettingResource extends Resource
{
    protected static ?string $model = TenantSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    
    protected static ?string $navigationLabel = 'Store Settings';

    public static function form(Schema $schema): Schema
    {
        return SettingsForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSettings::route('/'),
        ];
    }
}