<?php

namespace App\Filament\TenantAdmin\Resources\Settings\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Schemas\Schema;
class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('store_name')->label('Store Name'),
                TextColumn::make('contact_email')->label('Email'),
            ])
             ->recordActions([
                EditAction::make(),
            ]);
    }
}