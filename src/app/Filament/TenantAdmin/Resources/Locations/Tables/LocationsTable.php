<?php

namespace App\Filament\TenantAdmin\Resources\Locations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LocationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Location Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'branch'       => 'success',
                        'warehouse'    => 'warning',
                        'pickup_point' => 'info',
                        default        => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'branch'       => 'Branch',
                        'warehouse'    => 'Warehouse',
                        'pickup_point' => 'Pickup Point',
                        default        => $state,
                    }),

                TextColumn::make('address.city')
                    ->label('City')
                    ->sortable(),

                TextColumn::make('address.address_line_1')
                    ->label('Address')
                    ->limit(30)
                    ->toggleable(),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->copyable(),

                IconColumn::make('is_visible_to_customers')
                    ->label('Visible to Customers')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_pickup_point')
                    ->label('Pickup Point')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Date Added')
                    ->dateTime('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Filter by Type')
                    ->options([
                        'branch'       => 'Branch',
                        'warehouse'    => 'Warehouse',
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