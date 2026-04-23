<?php

namespace App\Filament\TenantAdmin\Resources\ShippingZones\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;

class ShippingZonesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Sort Order')
                    ->numeric(),
                    
                TextColumn::make('name')
                    ->label('Zone Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('countries')
                    ->label('Countries')
                    ->badge()
                    ->separator(',')
                    ->limitList(3)
                    ->default('All Countries')
                    ->color(fn ($state) => $state === 'All Countries' ? 'gray' : 'primary'),

                TextColumn::make('methods_count')
                    ->counts('methods')
                    ->label('Methods')
                    ->badge()
                    ->color('info'),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('updated_at')
                    ->label('Last Update')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ]),
            ])
            ->reorderable('sort_order');
    }
}