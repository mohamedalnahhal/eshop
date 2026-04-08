<?php

namespace App\Filament\TenantAdmin\Resources\Categories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;


class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required(),

                Select::make('type')
                    ->options([
                        'main' => 'Main',
                        'sub' => 'Sub-category',
                    ])
                    ->live()
                    ->required(),

                Select::make('parent_id')
                    ->label('Parent Category')
                    ->relationship('parent', 'name', fn($query) => $query->whereNull('parent_id'))
                    ->visible(fn(Get $get) => $get('type') === 'sub')
                    ->required(fn(Get $get) => $get('type') === 'sub'),
            ]);
    }
}