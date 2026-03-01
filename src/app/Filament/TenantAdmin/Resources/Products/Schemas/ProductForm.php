<?php

namespace App\Filament\TenantAdmin\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProductForm
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
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
