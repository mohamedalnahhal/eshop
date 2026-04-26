<?php

namespace App\Filament\SuperAdmin\Resources\Subscriptions\Schemas;

use App\Services\Money\MoneyService;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Plan Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(100)
                            ->columnSpanFull(),

                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix(app(MoneyService::class)->getSymbol(config('app.default_currency', 'USD')))
                            ->formatStateUsing(fn ($state) => blank($state) ? null : app(MoneyService::class)->fromMinor((int) $state))
                            ->dehydrateStateUsing(fn ($state) => blank($state) ? 0 : app(MoneyService::class)->toMinor((float) $state)),

                        TextInput::make('duration_days')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->suffix('days')
                            ->label('Duration'),

                        TextInput::make('max_products')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->helperText('0 = unlimited')
                            ->label('Max Products'),


                    ]),

                Section::make('Features')
                    ->schema([
                        TagsInput::make('features')
                            ->placeholder('Add a feature and press Enter')
                            ->helperText('List each plan feature separately')
                            ->default([]),
                    ]),
            ]);
    }
}
