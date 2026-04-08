<?php

namespace App\Filament\SuperAdmin\Resources\Subscriptions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('duration_days')
                    ->required()
                    ->numeric(),
                TextInput::make('max_products')
                    ->required()
                    ->numeric(),
                Textarea::make('features')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
