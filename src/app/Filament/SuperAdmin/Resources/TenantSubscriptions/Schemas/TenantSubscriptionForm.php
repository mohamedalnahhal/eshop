<?php

namespace App\Filament\SuperAdmin\Resources\TenantSubscriptions\Schemas;

use App\Models\Subscription;
use App\Models\Tenant;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TenantSubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->label('Shop')
                    ->options(fn () => Tenant::query()->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->required(),

                Select::make('subscription_id')
                    ->label('Subscription Plan')
                    ->options(fn () => Subscription::all()
                        ->mapWithKeys(fn ($s) => [
                            $s->id => "{$s->name} — {$s->formatted_price} / {$s->duration_days}d",
                        ])
                        ->toArray()
                    )
                    ->searchable()
                    ->required(),

                Select::make('status')
                    ->options(\App\Enums\SubscriptionStatus::class)
                    ->required(),

                DateTimePicker::make('starts_at')
                    ->required(),

                DateTimePicker::make('ends_at')
                    ->required(),

                Toggle::make('trial')
                    ->label('Start as Trial')
                    ->helperText('Sets status to Trialing instead of Pending')
                    ->visibleOn('create')
                    ->default(false)
                    ->dehydrated(false),
            ]);
    }
}
