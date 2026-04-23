<?php

namespace App\Filament\TenantAdmin\Resources\ShippingZones\Schemas;

use App\Enums\ShippingRateType;
use App\Services\Money\MoneyService;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class MethodsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Shipping Method')
                ->description('Define the method and its rate rules.')
                ->schema([
                    TextInput::make('name')
                        ->label('Method Name')
                        ->required()
                        ->maxLength(100),

                    TextInput::make('estimated_delivery')
                        ->label('Estimated Delivery Time')
                        ->maxLength(100),

                    Textarea::make('description')
                        ->label('Description')
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),

                    Repeater::make('rates')
                        ->relationship('rates')
                        ->label('Rates Conditions')
                        ->addActionLabel('Add Rate Rule')
                        ->columns(2)
                        ->columnSpanFull()
                        ->collapsible()
                        ->schema([
                            Select::make('rate_type')
                                ->label('Rate Type')
                                ->options(ShippingRateType::class)
                                ->required()
                                ->default('flat_rate')
                                ->live(),

                            TextInput::make('fee')
                                ->label('Shipping Fee')
                                ->numeric()
                                ->required()
                                ->hidden(fn (Get $get) => $get('rate_type') === ShippingRateType::FREE)
                                ->formatStateUsing(fn ($state) => blank($state) ? null : app(MoneyService::class)->fromMinor((int) $state))
                                ->dehydrateStateUsing(fn ($state) => blank($state) ? null : app(MoneyService::class)->toMinor((float) $state)),

                            TextInput::make('free_above')
                                ->label('Free Shipping Above')
                                ->numeric()
                                ->hidden(fn (Get $get) => $get('rate_type') === ShippingRateType::FREE)
                                ->formatStateUsing(fn ($state) => blank($state) ? null : app(MoneyService::class)->fromMinor((int) $state))
                                ->dehydrateStateUsing(fn ($state) => blank($state) ? null : app(MoneyService::class)->toMinor((float) $state)),

                            TextInput::make('condition_min')
                                ->label('Min Condition')
                                ->numeric()
                                ->hidden(fn (Get $get) => ! in_array($get('rate_type'), [ShippingRateType::PRICE_BASED, ShippingRateType::WEIGHT_BASED], true))
                                ->formatStateUsing(fn ($state, Get $get) => (blank($state) || $get('rate_type') !== ShippingRateType::PRICE_BASED) 
                                    ? $state 
                                    : app(MoneyService::class)->fromMinor((int) $state))
                                ->dehydrateStateUsing(fn ($state, Get $get) => (blank($state) || $get('rate_type') !== ShippingRateType::PRICE_BASED) 
                                    ? $state 
                                    : app(MoneyService::class)->toMinor((float) $state)),

                            TextInput::make('condition_max')
                                ->label('Max Condition')
                                ->numeric()
                                ->hidden(fn (Get $get) => ! in_array($get('rate_type'), [ShippingRateType::PRICE_BASED, ShippingRateType::WEIGHT_BASED], true))
                                ->formatStateUsing(fn ($state, Get $get) => (blank($state) || $get('rate_type') !== ShippingRateType::PRICE_BASED) 
                                    ? $state 
                                    : app(MoneyService::class)->fromMinor((int) $state))
                                ->dehydrateStateUsing(fn ($state, Get $get) => (blank($state) || $get('rate_type') !== ShippingRateType::PRICE_BASED) 
                                    ? $state 
                                    : app(MoneyService::class)->toMinor((float) $state)),
                        ]),
                ]),
        ]);
    }
}