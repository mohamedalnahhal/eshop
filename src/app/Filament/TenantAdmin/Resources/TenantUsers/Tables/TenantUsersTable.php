<?php

namespace App\Filament\TenantAdmin\Resources\TenantUsers\Tables;

use App\Enums\TenantUserRole;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TenantUsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('user.avatar_url')
                    ->label('')
                    ->circular()
                    ->grow(false),

                TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->color('gray'),

                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->formatStateUsing(fn ($state) => self::resolveRole($state)->getLabel())
                    ->color(fn ($state) => match (self::resolveRole($state)) {
                        TenantUserRole::OWNER => 'warning',
                        TenantUserRole::MANAGER => 'primary',
                        TenantUserRole::STAFF => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->since()
                    ->sortable()
                    ->color('gray'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn ($record) => self::resolveRole($record->role) === TenantUserRole::OWNER),
            ])
            ->defaultSort('created_at', 'desc');
    }

    private static function resolveRole(mixed $raw): TenantUserRole
    {
        return $raw instanceof TenantUserRole ? $raw : TenantUserRole::from($raw);
    }
}