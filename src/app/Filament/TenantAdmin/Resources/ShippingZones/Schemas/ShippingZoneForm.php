<?php

namespace App\Filament\TenantAdmin\Resources\ShippingZones\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ShippingZoneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Zone Details')
                ->description('Define the geographical zone for shipping.')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Zone Name')
                        ->placeholder('e.g., Domestic, Europe, Rest of World')
                        ->required()
                        ->maxLength(100),

                    Select::make('countries')
                        ->label('Countries')
                        ->multiple()
                        ->searchable()
                        ->options(config('countries'))
                        ->required(),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}