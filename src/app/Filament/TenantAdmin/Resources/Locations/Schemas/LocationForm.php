<?php

namespace App\Filament\TenantAdmin\Resources\Locations\Schemas;

use App\Enums\AddressType;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Dotswan\MapPicker\Fields\Map;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Group::make()
                    ->columnSpan(1)
                    ->schema([
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
                        Section::make('Map & Coordinates')
                            ->description('Pinpoint the location on the map')
                            ->relationship('address')
                            ->schema([
                                Map::make('location')
                                    ->label('Pick Location on Map')
                                    ->columnSpanFull()
                                    ->defaultLocation(latitude: 31.9, longitude: 35.2)
                                    ->live()
                                    ->dehydrated(false) // Prevents Filament from trying to save this virtual field to the DB
                                    ->afterStateUpdated(function (?array $state, Set $set) {
                                        if (isset($state['lat'], $state['lng'])) {
                                            $set('lat', $state['lat']);
                                            $set('lng', $state['lng']);
                                        }
                                    })
                                    ->afterStateHydrated(function (?array $state, Set $set, Get $get) {
                                        // Grab lat/lng directly from the sibling fields in this relationship context
                                        $lat = $get('lat');
                                        $lng = $get('lng');
                                    
                                        if ($lat !== null && $lng !== null) {
                                            $set('location', [
                                                'lat' => (float) $lat,
                                                'lng' => (float) $lng,
                                            ]);
                                        }
                                    }),

                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('lat')
                                            ->label('Latitude')
                                            ->numeric()
                                            ->dehydrated(),

                                        TextInput::make('lng')
                                            ->label('Longitude')
                                            ->numeric()
                                            ->dehydrated(),
                                    ]),
                            ]),
                    ]),
                Group::make()
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Address Details')
                            ->description('Location street address information')
                            ->relationship('address')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Address Name')
                                    ->placeholder('e.g., Main Branch Address')
                                    ->required()
                                    ->maxLength(100),

                                Select::make('country')
                                    ->label('Country')
                                    ->options(config('countries'))
                                    ->searchable()
                                    ->required()
                                    ->native(false),

                                TextInput::make('postal_code')
                                    ->label('Postal Code')
                                    ->maxLength(20),

                                TextInput::make('city')
                                    ->label('City')
                                    ->required()
                                    ->maxLength(100),

                                TextInput::make('state')
                                    ->label('State / Region')
                                    ->maxLength(100),

                                TextInput::make('line_1')
                                    ->label('Address Line 1')
                                    ->required()
                                    ->maxLength(255),
                                    
                                TextInput::make('line_2')
                                    ->label('Address Line 2')
                                    ->maxLength(255),

                                Select::make('type')
                                    ->label('Address Type')
                                    ->options(AddressType::class)
                                    ->default(AddressType::PICKUP->value)
                                    ->native(false)
                                    ->required(),
                            ]),
                    ]),
            ]);
    }
}
