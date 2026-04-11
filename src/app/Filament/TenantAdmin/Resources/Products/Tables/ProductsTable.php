<?php

namespace App\Filament\TenantAdmin\Resources\Products\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->limit(7)
                    ->tooltip(fn ($state): string => $state) 
                    ->copyable() 
                    ->fontFamily('mono')
                    ->searchable(),
                ImageColumn::make('media.file_path')
                    ->label('image')
                    ->disk('public')
                    ->stacked()
                    ->limit(1),
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
                TrashedFilter::make(),
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
                        DeleteAction::make(),
                        RestoreAction::make(),
                        ForceDeleteAction::make(), 
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
