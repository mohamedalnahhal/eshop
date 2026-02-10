<?php

namespace App\Filament\TenantAdmin\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('final_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Select::make('status')
                    ->options(OrderStatus::class)
                    ->required(),
                TextInput::make('user_id')
                    ->required(),
                TextInput::make('tenant_id')
                    ->required(),
                TextInput::make('shipping_address_id')
                    ->required(),
            ]);
    }
}
