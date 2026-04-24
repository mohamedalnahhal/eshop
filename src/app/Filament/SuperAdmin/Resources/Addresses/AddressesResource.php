<?php

namespace App\Filament\SuperAdmin\Resources\Addresses;

use App\Filament\SuperAdmin\Resources\Addresses\Pages\CreateAddresses;
use App\Filament\SuperAdmin\Resources\Addresses\Pages\EditAddresses;
use App\Filament\SuperAdmin\Resources\Addresses\Pages\ListAddresses;
use App\Filament\SuperAdmin\Resources\Addresses\Schemas\AddressesForm;
use App\Filament\SuperAdmin\Resources\Addresses\Tables\AddressesTable;
use App\Models\Address;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AddressesResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-identification';
    protected static string|\UnitEnum|null $navigationGroup = 'Shipping';
    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return AddressesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AddressesTable::configure($table);
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
            'index' => ListAddresses::route('/'),
            'create' => CreateAddresses::route('/create'),
            'edit' => EditAddresses::route('/{record}/edit'),
        ];
    }
}
