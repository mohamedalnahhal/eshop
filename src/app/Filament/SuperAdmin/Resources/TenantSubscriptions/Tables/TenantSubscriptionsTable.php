<?php

namespace App\Filament\SuperAdmin\Resources\TenantSubscriptions\Tables;

use App\Enums\SubscriptionStatus;
use App\Services\Subscription\SubscriptionService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TenantSubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant.name')
                    ->label('Shop Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subscription.name')
                    ->label('Plan')
                    ->badge()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => $state->color()),

                TextColumn::make('subscription.price')
                    ->label('Fee')
                    ->formatStateUsing(fn ($state) => '$' . number_format($state / 100, 2))
                    ->sortable(),

                TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($record) => $record->ends_at->isPast() ? 'danger' : 'success'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(SubscriptionStatus::class),
            ])
            ->recordActions([
                EditAction::make(),
                ActionGroup::make([
                    Action::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => !in_array($record->status, [SubscriptionStatus::ACTIVE]))
                        ->action(function ($record) {
                            app(SubscriptionService::class)->activate($record);
                            Notification::make()->title('Subscription activated')->success()->send();
                        }),

                    Action::make('cancel')
                        ->label('Cancel')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => !in_array($record->status, [SubscriptionStatus::CANCELLED, SubscriptionStatus::EXPIRED]))
                        ->action(function ($record) {
                            app(SubscriptionService::class)->cancel($record);
                            Notification::make()->title('Subscription cancelled')->warning()->send();
                        }),

                    Action::make('renew')
                        ->label('Renew')
                        ->icon('heroicon-o-arrow-path')
                        ->color('info')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            app(SubscriptionService::class)->renew($record);
                            Notification::make()->title('Renewal subscription created (Pending)')->success()->send();
                        }),

                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
