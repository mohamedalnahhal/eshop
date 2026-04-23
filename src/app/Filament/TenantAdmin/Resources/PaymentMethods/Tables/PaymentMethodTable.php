<?php

namespace App\Filament\TenantAdmin\Resources\PaymentMethods\Tables;

use App\Enums\PaymentProvider;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentMethodTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Display Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('provider')
                    ->label('Provider')
                    ->badge()
                    ->formatStateUsing(fn (PaymentProvider $state) => $state->label())
                    ->color('primary'),

                TextColumn::make('config')
                    ->label('Config Keys')
                    // ->formatStateUsing(fn ($state) => $state ? count($state) . ' ' . str('key')->plural(count($state)) : '—')
                    ->color('gray'),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->since()
                    ->sortable()
                    ->color('gray'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateIcon('heroicon-o-credit-card')
            ->emptyStateHeading('No payment methods yet')
            ->emptyStateDescription('Add a payment method to start accepting payments.');
    }
}
