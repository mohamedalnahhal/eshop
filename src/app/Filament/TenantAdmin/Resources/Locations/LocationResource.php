<?php

namespace App\Filament\TenantAdmin\Resources\Locations;

use App\Filament\TenantAdmin\Resources\Locations\Pages\CreateLocation;
use App\Filament\TenantAdmin\Resources\Locations\Pages\EditLocation;
use App\Filament\TenantAdmin\Resources\Locations\Pages\ListLocations;
use App\Filament\TenantAdmin\Resources\Locations\Schemas\LocationForm;
use App\Filament\TenantAdmin\Resources\Locations\Tables\LocationsTable;
use App\Models\Location;
use BackedEnum; 
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Location';

    public static function form(Schema $schema): Schema
    {
        return LocationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LocationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLocations::route('/'),
            'create' => CreateLocation::route('/create'),
            'edit' => EditLocation::route('/{record}/edit'),
        ];
    }
}
