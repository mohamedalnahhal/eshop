<?php

namespace App\Filament\TenantAdmin\Resources\Locations\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class LocationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Location name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'branch' => 'success',
                        'warehouse' => 'warning',
                        'pickup_point' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'branch' => 'Branch',
                        'warehouse' => 'Warehouse',
                        'pickup_point' => 'Pickup Point',
                        default => $state,
                    }),

                TextColumn::make('city')
                    ->label('City')
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('phone number')
                    ->copyable(),

                IconColumn::make('is_visible_to_customers')
                    ->label('Visible to customers')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Date Added')
                    ->dateTime('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Filter by type')
                    ->options([
                        'branch' => 'Branch',
                        'warehouse' => 'Warehouse',
                        'pickup_point' => 'Pickup Point',
                    ]),
            ])
            ->actions([
                EditAction::make(), 
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}