<?php

namespace App\Filament\TenantAdmin\Resources\Orders\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('user.username')
                ->label('User')
                ->collapsible(),
                Group::make('status')
                ->collapsible(),
            ])
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID')
                    ->limit(7)
                    ->tooltip(fn ($state): string => $state) 
                    ->copyable() 
                    ->fontFamily('mono')
                    ->searchable(),
                TextColumn::make('user.username')
                    ->searchable(),
                TextColumn::make('items_summary')
                    ->label('Order Items')
                    ->getStateUsing(fn ($record) => $record->items->map(fn ($item) => "{$item->quantity}x " . ($item->product->name ?? 'Unknown')))
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('items.product', function (Builder $q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('total_price')
                    ->money()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('discount')
                    ->numeric()
                    ->prefix('%')
                    ->sortable(),
                TextColumn::make('final_price')
                    ->money()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('address')
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->shippingAddress->name;
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
               ActionGroup::make([
                    DeleteAction::make()->label('Archive Order'),
                    RestoreAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
