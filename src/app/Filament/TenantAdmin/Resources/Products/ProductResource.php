<?php

namespace App\Filament\TenantAdmin\Resources\Products;

use App\Filament\TenantAdmin\Resources\Products\Pages\CreateProduct;
use App\Filament\TenantAdmin\Resources\Products\Pages\EditProduct;
use App\Filament\TenantAdmin\Resources\Products\Pages\ListProducts;
use App\Filament\TenantAdmin\Resources\Products\Schemas\ProductForm;
use App\Filament\TenantAdmin\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';
    protected static string|\UnitEnum|null $navigationGroup = 'Products';

    protected static ?string $recordTitleAttribute = 'name';


    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
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
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() === 0? 'No Prodcuts' : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}
