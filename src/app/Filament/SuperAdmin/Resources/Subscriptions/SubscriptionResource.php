<?php

namespace App\Filament\SuperAdmin\Resources\Subscriptions;

use App\Filament\SuperAdmin\Resources\Subscriptions\Pages\CreateSubscription;
use App\Filament\SuperAdmin\Resources\Subscriptions\Pages\EditSubscription;
use App\Filament\SuperAdmin\Resources\Subscriptions\Pages\ListSubscriptions;
use App\Filament\SuperAdmin\Resources\Subscriptions\Schemas\SubscriptionForm;
use App\Filament\SuperAdmin\Resources\Subscriptions\Tables\SubscriptionsTable;
use App\Models\Subscription;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Subscription Plans';
    protected static ?string $modelLabel = 'Subscription Plan';
    protected static ?string $pluralModelLabel = 'Subscription Plans';

    protected static string|\UnitEnum|null $navigationGroup = 'Financials';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SubscriptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubscriptionsTable::configure($table);
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
            'index' => ListSubscriptions::route('/'),
            'create' => CreateSubscription::route('/create'),
            'edit' => EditSubscription::route('/{record}/edit'),
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
