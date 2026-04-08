<?php

namespace App\Filament\TenantAdmin\Resources\Locations\Schemas;

use App\Enums\AddressType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Dotswan\MapPicker\Fields\Map;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Location Information')
                    ->description('Basic branch or warehouse data')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Location Name')
                                    ->placeholder('Main Branch')
                                    ->required()
                                    ->maxLength(100),

                                Select::make('type')
                                    ->label('Location Type')
                                    ->options([
                                        'branch'       => 'Sales Branch',
                                        'warehouse'    => 'Warehouse',
                                        'pickup_point' => 'Pickup Point',
                                    ])
                                    ->default('branch')
                                    ->native(false)
                                    ->required(),

                                TextInput::make('phone')
                                    ->label('Contact Phone')
                                    ->tel(),

                                Toggle::make('is_visible_to_customers')
                                    ->label('Show to Customers?')
                                    ->default(true),

                                Toggle::make('is_pickup_point')
                                    ->label('Is Pickup Point?')
                                    ->default(false),
                            ]),
                    ]),

                Section::make('Address & Coordinates')
                    ->description('Location address and map pin')
                    ->relationship('address')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Address Name')
                                    ->placeholder('e.g., Main Branch Address')
                                    ->required()
                                    ->maxLength(100)
                                    ->columnSpanFull(),

                                TextInput::make('address_line_1')
                                    ->label('Street Address')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                TextInput::make('city')
                                    ->label('City')
                                    ->required()
                                    ->maxLength(100),

                                TextInput::make('state')
                                    ->label('State / Region')
                                    ->maxLength(100),

                                TextInput::make('postal_code')
                                    ->label('Postal Code')
                                    ->maxLength(20),

                                Select::make('country')
                                    ->label('Country')
                                    ->options(config('countries'))
                                    ->searchable()
                                    ->required()
                                    ->native(false),

                                Select::make('type')
                                    ->label('Address Type')
                                    ->options(AddressType::class)
                                    ->default(AddressType::PICKUP->value)
                                    ->native(false)
                                    ->required(),
                            ]),

                        Map::make('location')
                            ->label('Pick Location on Map')
                            ->columnSpanFull()
                            ->defaultLocation(latitude: 31.9, longitude: 35.2)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (isset($state['lat'], $state['lng'])) {
                                    $set('lat', $state['lat']);
                                    $set('lng', $state['lng']);
                                }
                            })
                            ->afterStateHydrated(function ($state, callable $set, $record) {
                                if ($record?->address) {
                                    $set('location', [
                                        'lat' => $record->address->lat,
                                        'lng' => $record->address->lng,
                                    ]);
                                }
                            }),

                        TextInput::make('lat')
                            ->label('Latitude')
                            ->numeric()
                            ->dehydrated(),

                        TextInput::make('lng')
                            ->label('Longitude')
                            ->numeric()
                            ->dehydrated(),
                    ]),
            ]);
    }
}
