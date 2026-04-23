<?php

namespace App\Filament\TenantAdmin\Resources\ShippingZones;

use App\Filament\TenantAdmin\Resources\ShippingZones\Pages\CreateShippingZone;
use App\Filament\TenantAdmin\Resources\ShippingZones\Pages\EditShippingZone;
use App\Filament\TenantAdmin\Resources\ShippingZones\Pages\ListShippingZones;
use App\Filament\TenantAdmin\Resources\ShippingZones\RelationManagers\MethodsRelationManager;
use App\Filament\TenantAdmin\Resources\ShippingZones\Schemas\ShippingZoneForm;
use App\Filament\TenantAdmin\Resources\ShippingZones\Tables\ShippingZonesTable;
use App\Models\ShippingZone;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ShippingZoneResource extends Resource
{
    protected static ?string $model = ShippingZone::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-americas';
    protected static string|UnitEnum|null $navigationGroup = 'Shop';

    protected static ?string $modelLabel = 'Shipping Rule';
    protected static ?string $pluralModelLabel = 'Shipping Rules';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ShippingZoneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShippingZonesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            MethodsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShippingZones::route('/'),
            'create' => CreateShippingZone::route('/create'),
            'edit' => EditShippingZone::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}