<?php

namespace App\Filament\SuperAdmin\Resources\Locations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->label('Shop')
                    ->relationship('tenant', 'name') 
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),
                    
                TextInput::make('name')
                    ->label('Branch Name / Location Name')
                    ->required()
                    ->maxLength(100)
                    ->columnSpanFull(),
                    
                Toggle::make('is_pickup_point')
                    ->label('Is this a pickup point?')
                    ->default(false)
                    ->columnSpanFull(),
            ]);
    }
}