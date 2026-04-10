<?php

namespace App\Filament\SuperAdmin\Resources\PaymentMethods;

use App\Filament\SuperAdmin\Resources\PaymentMethods\Pages;
use App\Filament\SuperAdmin\Resources\PaymentMethods\Schemas\PaymentMethodForm;
use App\Filament\SuperAdmin\Resources\PaymentMethods\Tables\PaymentMethodTable;
use App\Models\PaymentMethod;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Payment Methods';

    public static function form(Schema $schema): Schema
    {
        return PaymentMethodForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentMethodTable::configure($table);
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
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}