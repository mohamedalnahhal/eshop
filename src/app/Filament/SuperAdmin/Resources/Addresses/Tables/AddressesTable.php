<?php

namespace App\Filament\SuperAdmin\Resources\Addresses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class AddressesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('addressable_type')
                    ->label('Linked To (Type)')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('addressable_id')
                    ->label('Linked ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('name')
                    ->label('Address Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('address_line_1')
                    ->label('Address')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('city')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('state')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('country')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('postal_code')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('lat')
                    ->label('Latitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('lng')
                    ->label('Longitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
