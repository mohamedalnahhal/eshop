<?php

namespace App\Filament\SuperAdmin\Resources\Locations;

use App\Filament\SuperAdmin\Resources\Locations\Pages\ListLocations;
use App\Filament\SuperAdmin\Resources\Locations\Schemas\LocationForm;
use App\Filament\SuperAdmin\Resources\Locations\Tables\LocationsTable;
use App\Models\Location;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';
    protected static string|\UnitEnum|null $navigationGroup = 'Shipping';
    protected static ?int $navigationSort = 9;

    protected static ?string $navigationLabel = 'Shop Locations';
    protected static ?string $modelLabel = 'Shop Location';
    protected static ?string $pluralModelLabel = 'Shop Locations';

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
        ];
    }
}
