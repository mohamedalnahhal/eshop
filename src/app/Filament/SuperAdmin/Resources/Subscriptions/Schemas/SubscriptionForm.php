<?php

namespace App\Filament\SuperAdmin\Resources\Subscriptions\Schemas;

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
                            ->prefix('$')
                            ->helperText('Price in cents (e.g. 2999 = $29.99)')
                            ->suffix('cents'),

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
