<?php

namespace App\Filament\SuperAdmin\Resources\Addresses\Schemas;

use App\Enums\AddressType;
use App\Models\Location;
use App\Models\Order;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MorphToSelect;
use App\Models\User;
use Filament\Schemas\Schema;

class AddressesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Group::make()
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Address Details')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Address Name')
                                    ->placeholder('e.g., Main Headquarters, Home')
                                    ->required()
                                    ->maxLength(100),

                                Select::make('type')
                                    ->label('Address Type')
                                    ->options(AddressType::class)
                                    ->required()
                                    ->native(false),
                            ])
                            ->columns(2),

                        Section::make('Coordinates')
                            ->description('Geographic coordinates for mapping.')
                            ->schema([
                                TextInput::make('lat')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->step('0.00000001'),

                                TextInput::make('lng')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->step('0.00000001'),
                            ])
                            ->columns(2)
                            ->collapsed(),

                        Section::make('Morph Relation')
                            ->description('Manually link this address to a specific record.')
                            ->schema([
                                MorphToSelect::make('addressable')
                                    ->label('Linked Record')
                                    ->types([
                                        MorphToSelect\Type::make(User::class)
                                            ->titleAttribute('username'),
                                            
                                        MorphToSelect\Type::make(Location::class)
                                            ->titleAttribute('name'),
                                        
                                        MorphToSelect\Type::make(Order::class)
                                            ->titleAttribute('id')
                                    ])
                                    ->searchable()
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->collapsed(),
                    ]),
                
                Group::make()
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Location')
                            ->schema([
                                TextInput::make('address_line_1')
                                    ->label('Street Address')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                TextInput::make('city')
                                    ->required()
                                    ->maxLength(100),

                                TextInput::make('state')
                                    ->required()
                                    ->maxLength(100),

                                TextInput::make('postal_code')
                                    ->required()
                                    ->maxLength(20),

                                Select::make('country')
                                    ->label('Country')
                                    ->options(config('countries'))
                                    ->searchable()
                                    ->required()
                                    ->native(false),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
