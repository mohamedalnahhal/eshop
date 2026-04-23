<?php

namespace App\Filament\TenantAdmin\Resources\PaymentLogs\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;


class PaymentLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_reference')
                ->label('Transaction ID')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('payable')
                    ->label('Reference')
                    ->formatStateUsing(function ($record) {
        return $record->payable
            ? class_basename($record->payable) . ' #' . $record->payable->id
            : '-';
    }),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('USD') 
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed', 'paid' => 'success',
                        'pending' => 'warning',
                        'failed', 'declined' => 'danger',
                        'refunded' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc') 
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
                SelectFilter::make('payment_method')
                    ->options([
                        'stripe' => 'Stripe',
                        'paypal' => 'PayPal',
                        'cash_on_delivery' => 'Cash on Delivery',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(), 
            ])
            ->groupedBulkActions([]); 
    }
}