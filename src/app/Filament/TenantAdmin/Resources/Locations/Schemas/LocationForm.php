<?php

namespace App\Filament\TenantAdmin\Resources\Locations\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Dotswan\MapPicker\Fields\Map;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        
                        Section::make('Location Information')
                            ->description('Enter detailed branch or warehouse data')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Location Name')
                                            ->placeholder('Main Branch')
                                            ->prefix('🏷️') 
                                            ->required()
                                            ->maxLength(100),

                                        Select::make('type')
                                            ->label('Location Type')
                                            ->options([
                                                'branch' => 'Sales Branch',
                                                'warehouse' => 'Warehouse',
                                                'pickup_point' => 'Pickup Point'
                                            ])
                                            ->default('branch')
                                            ->native(false), 
                                        
                                        TextInput::make('city')
                                            ->label('City')
                                            ->placeholder('Riyadh'),

                                        TextInput::make('phone') 
                                            ->label('Contact Phone')
                                            ->tel()
                                            ->prefix('📞'),

                                        TextInput::make('address_line')
                                            ->label('Full Address')
                                            ->placeholder('Street, District, Building number')
                                            ->required()
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpan(3), 

                        Section::make('Map & Coordinates')
                            ->description('Click on the map to set the location pin.')
                            ->schema([
                                Map::make('location') 
                                    ->label('Pick Location on Map')
                                    ->columnSpanFull()
                                    ->defaultLocation(latitude: 31.5, longitude: 34.4)
                                    ->live() 
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (isset($state['lat']) && isset($state['lng'])) {
                                            $set('lat', $state['lat']);
                                            $set('lng', $state['lng']);
                                        }
                                    })
                                    ->afterStateHydrated(function ($state, callable $set, $record) {
                                        if ($record) {
                                            $set('location', [
                                                'lat' => $record->lat,
                                                'lng' => $record->lng,
                                            ]);
                                        }
                                    })
                                    ->extraAttributes([
                                        'class' => 'h-96 rounded-2xl shadow-sm border border-gray-100 overflow-hidden',
                                    ]),

                                TextInput::make('lat')
                                    ->required()
                                    ->numeric()
                                    ->dehydrated(), 

                                TextInput::make('lng')
                                    ->required()
                                    ->numeric()
                                    ->dehydrated(), 

                                Toggle::make('is_visible_to_customers')
                                    ->label('Show to Customers?')
                                    ->default(true), 

                                Toggle::make('is_pickup_point')
                                    ->label('Is Pickup Point?')
                                    ->default(false),
                            ])
                            ->columnSpan(3),
                    ]),
            ]);
    }
}