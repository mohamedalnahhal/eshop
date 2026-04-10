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
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressesResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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

  public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
{
    return parent::getEloquentQuery()
        ->withoutGlobalScopes([
            \Illuminate\Database\Eloquent\SoftDeletingScope::class,
        ]);
}
}
