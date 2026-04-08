<?php

namespace App\Filament\TenantAdmin\Resources\Settings\Schemas;

use Filament\Schemas\Schema; 
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;

class SettingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Store information')
                    ->description('Maintaining basic tenant data')
                    ->schema([
                        TextInput::make('store_name')
                            ->label('Store Name')
                            ->required(),
                            
                        TextInput::make('contact_email')
                            ->label('Owner Email')
                            ->email(),
                            
                        TextInput::make('contact_phone')
                            ->label('Phone Number'),
                    ])->columns(2),
            ]);
    }
}