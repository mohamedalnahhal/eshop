<?php

namespace App\Filament\TenantAdmin\Resources\ShippingZones\Schemas;

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
                                ->options([
                                    'flat_rate' => 'Flat Rate',
                                    'free' => 'Free Shipping',
                                    'price_based' => 'Based on Order Price',
                                    'weight_based' => 'Based on Order Weight',
                                ])
                                ->required()
                                ->default('flat_rate')
                                ->live(),

                            TextInput::make('fee')
                                ->label('Shipping Fee')
                                ->numeric()
                                ->required()
                                ->hidden(fn (Get $get) => $get('rate_type') === 'free'),

                            TextInput::make('free_above')
                                ->label('Free Shipping Above')
                                ->numeric()
                                ->hidden(fn (Get $get) => $get('rate_type') !== 'free'),

                            TextInput::make('condition_min')
                                ->label('Min Condition')
                                ->numeric()
                                ->hidden(fn (Get $get) => ! in_array($get('rate_type'), ['price_based', 'weight_based'], true)),

                            TextInput::make('condition_max')
                                ->label('Max Condition')
                                ->numeric()
                                ->hidden(fn (Get $get) => ! in_array($get('rate_type'), ['price_based', 'weight_based'], true)),
                        ]),
                ]),
        ]);
    }
}