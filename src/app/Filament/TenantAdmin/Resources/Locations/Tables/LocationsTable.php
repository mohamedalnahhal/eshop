<?php

namespace App\Filament\TenantAdmin\Resources\Locations\Tables;

use Filament\Actions\ActionGroup;
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
                TextColumn::make('id')
                    ->label('ID')
                    ->formatStateUsing(fn ($state) => '...' . substr($state, -7))
                    ->tooltip(fn ($state): string => $state) 
                    ->copyable() 
                    ->fontFamily('mono')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Location Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('address.country')
                    ->label('Country')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('address.postal_code')
                    ->label('Postal Code')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('address.city')
                    ->label('City')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('address.state')
                    ->label('State')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('address.line_1')
                    ->label('Line 1')
                    ->searchable()
                    ->limit(30),
                    
                TextColumn::make('address.line_2')
                    ->label('Line 2')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),


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
            ->recordActions([
                EditAction::make(),
                ActionGroup::make([
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ])
                ->label('Actions'),
            ]);
    }
}