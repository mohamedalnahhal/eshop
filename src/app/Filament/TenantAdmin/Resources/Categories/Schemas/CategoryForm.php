<?php

namespace App\Filament\TenantAdmin\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tenant_id')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('type')
                    ->options(['main' => 'Main', 'sub' => 'Sub', 'collection' => 'Collection', 'hidden' => 'Hidden'])
                    ->default('main')
                    ->required(),
            ]);
    }
}
