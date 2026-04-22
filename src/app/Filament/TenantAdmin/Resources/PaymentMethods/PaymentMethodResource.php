<?php

namespace App\Filament\TenantAdmin\Resources\PaymentMethods;

use App\Enums\TenantPermission;
use App\Filament\TenantAdmin\Resources\PaymentMethods\Pages\CreatePaymentMethod;
use App\Filament\TenantAdmin\Resources\PaymentMethods\Pages\EditPaymentMethod;
use App\Filament\TenantAdmin\Resources\PaymentMethods\Pages\ListPaymentMethods;
use App\Filament\TenantAdmin\Resources\PaymentMethods\Schemas\PaymentMethodForm;
use App\Filament\TenantAdmin\Resources\PaymentMethods\Tables\PaymentMethodTable;
use App\Models\PaymentMethod;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';
    protected static ?int $navigationSort = 96;
    protected static string|\UnitEnum|null $navigationGroup = 'Shop Settings';
    protected static ?string $navigationLabel = 'Payment Methods';
    protected static ?string $modelLabel = 'Payment Method';
    protected static ?string $pluralModelLabel = 'Payment Methods';
    protected static ?string $recordTitleAttribute = 'name';

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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPaymentMethods::route('/'),
            'create' => CreatePaymentMethod::route('/create'),
            'edit'   => EditPaymentMethod::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->tenantUserFor(tenant('id'))
            ?->can(TenantPermission::MANAGE_SETTINGS) ?? false;
    }
}
