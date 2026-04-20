<?php

namespace App\Filament\TenantAdmin\Resources\Orders\Tables;

use App\Services\Money\MoneyService;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use App\Enums\OrderStatus;
use App\Services\Orders\OrderService;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('customer.email')
                    ->label('Customer')
                    ->collapsible(),
                Group::make('status')
                    ->collapsible(),
            ])
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID')
                    ->formatStateUsing(fn ($state) => '...' . substr($state, -7))
                    ->tooltip(fn ($state): string => $state) 
                    ->copyable() 
                    ->fontFamily('mono')
                    ->searchable()
                    ->tooltip(fn ($state) => $state)
                    ->copyableState(fn ($state) => $state),
                TextColumn::make('customer.email')
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->customer?->email ?? $record->guest_email;
                    })
                    ->badge()
                    ->color(fn ($record) => $record->customer_id ? 'primary' : 'warning')
                    ->icon(fn ($record) => $record->customer_id ? 'heroicon-m-user' : 'heroicon-o-user')
                    ->tooltip(fn ($record) => $record->customer_id ? 'Registered' : 'Guest'),
                TextColumn::make('items_summary')
                    ->label('Order Items')
                    ->getStateUsing(function ($record) {
                        $appLocale = app()->getLocale();
                        $fallback  = config('app.fallback_locale', 'en');
                        return $record->items->map(function ($item) use ($appLocale, $fallback) {
                            $names = $item->product_name;
                            if (is_string($names)) {
                                $names = json_decode($names, true) ?? [];
                            }
                            if (is_array($names) && !empty($names)) {
                                $name = $names[$appLocale]
                                    ?? $names[$fallback]
                                    ?? array_values(array_filter($names))[0]
                                    ?? 'Unknown';
                            } else {
                                $name = $item->product?->name ?? 'Unknown';
                            }
                            return "{$item->quantity}x {$name}";
                        });
                    })
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('items', function (Builder $q) use ($search) {
                            $q->where('product_name', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('subtotal')
                    ->label('Items Total')
                    ->money()
                    ->sortable()
                    ->getStateUsing(fn ($record, MoneyService $moneyService) => $moneyService->formatOrderPrice($record, $record->subtotal))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('shipping_fees')
                    ->money()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->getStateUsing(fn ($record, MoneyService $moneyService) => $moneyService->formatOrderPrice($record, $record->shipping_fees)),
                TextColumn::make('discount')
                    ->money()
                    ->sortable()
                    ->getStateUsing(fn ($record, MoneyService $moneyService) => $moneyService->formatOrderPrice($record, $record->discount)),
                TextColumn::make('total')
                    ->label('Charged')
                    ->money()
                    ->sortable()
                    ->getStateUsing(fn ($record, MoneyService $moneyService) => $moneyService->formatOrderPrice($record, $record->total)),
                TextColumn::make('status')
                    ->badge(),
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
                Action::make('markAsPending')
                    ->label('Mark as Pending')
                    ->icon('heroicon-o-clock')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === OrderStatus::DRAFT)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        app(OrderService::class)->changeStatus($record, OrderStatus::PENDING);
                        Notification::make()->title('Order moved to Pending')->success()->send();
                    }),
                Action::make('markAsProcessing')
                    ->label('Process Order')
                    ->icon('heroicon-o-cog')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === OrderStatus::PENDING)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        app(OrderService::class)->changeStatus($record, OrderStatus::PROCESSING);
                        Notification::make()->title('Order is now Processing')->success()->send();
                    }),
                Action::make('markAsShipped')
                    ->label('Ship Order')
                    ->icon('heroicon-o-truck')
                    ->color('primary')
                    ->visible(fn ($record) => $record->status === OrderStatus::PROCESSING)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        app(OrderService::class)->changeStatus($record, OrderStatus::SHIPPED);
                        Notification::make()->title('Order Shipped')->success()->send();
                    }),
                Action::make('markAsDelivered')
                    ->label('Mark Delivered')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === OrderStatus::SHIPPED)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        app(OrderService::class)->changeStatus($record, OrderStatus::DELIVERED);
                        Notification::make()->title('Order Delivered')->success()->send();
                    }),
                Action::make('cancelOrder')
                    ->label('Cancel Order')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === OrderStatus::PENDING)
                    ->requiresConfirmation()
                    ->modalHeading('Cancel Order')
                    ->modalDescription('Are you sure you want to cancel this order? This cannot be undone.')
                    ->action(function ($record) {
                        app(OrderService::class)->changeStatus($record, OrderStatus::CANCELLED);
                        Notification::make()->title('Order Cancelled')->success()->send();
                    }),
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ]),
            ]);
    }
}
