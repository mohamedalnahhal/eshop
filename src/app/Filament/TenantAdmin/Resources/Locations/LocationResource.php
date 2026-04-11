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

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';
    protected static ?int $navigationSort = 2;
    protected static string|\UnitEnum|null $navigationGroup = 'Store';

    protected static ?string $recordTitleAttribute = 'name';

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

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() === 0? 'No Locations' : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}
