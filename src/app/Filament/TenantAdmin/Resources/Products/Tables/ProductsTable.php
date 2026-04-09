<?php

namespace App\Filament\TenantAdmin\Resources\Products\Tables;

use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('price')
                    ->money()
                    ->sortable(),
                TextColumn::make('stock')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state <= 0  => 'danger',
                        $state <= 10 => 'warning',
                        default      => 'success',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                ActionGroup::make([
                    Action::make('update_stock')
                        ->label('Update Stock')
                        ->icon('heroicon-o-arrow-up-circle')
                        ->color('primary')
                        ->schema([
                            Select::make('type')
                                ->label(__('Type'))
                                ->options([
                                    'add'    => 'Add',
                                    'remove' => 'Remove',
                                ])
                                ->required()
                                ->default('add'),
                            TextInput::make('amount')
                                ->label(__('Quantity'))
                                ->numeric()
                                ->required()
                                ->minValue(1),
                        ])
                        ->action(function ($record, array $data) {
                            if ($data['type'] === 'add') {
                                $record->increment('stock', $data['amount']);
                            } else {
                                $record->decrement('stock', $data['amount']);
                            }
    
                            Notification::make()
                                ->title(__('Stock Updated Successfully'))
                                ->success()
                                ->send();
                        }),    
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
