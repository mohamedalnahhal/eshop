<?php

namespace App\Filament\SuperAdmin\Resources\Addresses\Schemas;

use App\Enums\AddressType;
use App\Models\Customer;
use App\Models\Location;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
                                        MorphToSelect\Type::make(Customer::class)
                                            ->titleAttribute('email')
                                            ->modifyOptionsQueryUsing(fn (Builder $query) => $query->with('tenant'))
                                            ->getOptionLabelFromRecordUsing(fn (Model $record): string => "{$record->email} ({$record->tenant?->name})"),
                                            
                                        MorphToSelect\Type::make(Location::class)
                                            ->titleAttribute('name')
                                            ->modifyOptionsQueryUsing(fn (Builder $query) => $query->with('tenant'))
                                            ->getOptionLabelFromRecordUsing(fn (Model $record): string => "{$record->name} ({$record->tenant?->name})"),
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
                                Select::make('country')
                                    ->label('Country')
                                    ->options(config('countries'))
                                    ->searchable()
                                    ->required()
                                    ->native(false),

                                TextInput::make('postal_code')
                                    ->maxLength(20),

                                TextInput::make('city')
                                    ->required()
                                    ->maxLength(100),

                                TextInput::make('state')
                                    ->maxLength(100),

                                TextInput::make('line_1')
                                    ->label('Line 1')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                TextInput::make('line_2')
                                    ->label('Line 2')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Toggle::make('is_default')
                                    ->label('Default')
                                    ->default(false)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
