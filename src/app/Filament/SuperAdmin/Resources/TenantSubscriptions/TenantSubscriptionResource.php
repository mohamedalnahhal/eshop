<?php

namespace App\Filament\SuperAdmin\Resources\TenantSubscriptions;

use App\Filament\SuperAdmin\Resources\TenantSubscriptions\Pages\CreateTenantSubscription;
use App\Filament\SuperAdmin\Resources\TenantSubscriptions\Pages\EditTenantSubscription;
use App\Filament\SuperAdmin\Resources\TenantSubscriptions\Pages\ListTenantSubscriptions;
use App\Filament\SuperAdmin\Resources\TenantSubscriptions\Schemas\TenantSubscriptionForm;
use App\Filament\SuperAdmin\Resources\TenantSubscriptions\Tables\TenantSubscriptionsTable;
use App\Models\TenantSubscription;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TenantSubscriptionResource extends Resource
{
    protected static ?string $model = TenantSubscription::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Shop Subscriptions';
    protected static ?string $modelLabel = 'Subscription';
    protected static ?string $pluralModelLabel = 'Subscriptions';

    protected static string|\UnitEnum|null $navigationGroup = 'Financials';

    protected static ?int $navigationSort = 10;

    public static function getRecordTitle(?Model $record): string
    {
        if (!$record) return 'Shop Subscription';

        $tenantName = $record->tenant?->name ?? $record->tenant_id;
        $planName   = $record->subscription?->name ?? '';

        return "{$tenantName} — {$planName}";
    }

    public static function form(Schema $schema): Schema
    {
        return TenantSubscriptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TenantSubscriptionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTenantSubscriptions::route('/'),
            'create' => CreateTenantSubscription::route('/create'),
            'edit' => EditTenantSubscription::route('/{record}/edit'),
        ];
    }
}
