<?php

namespace App\Filament\TenantAdmin\Resources\Categories\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Category Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('parent.name')
                    ->label('Parent')
                    ->badge()
                    ->color('gray')
                    ->placeholder('Primary'),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'main' => 'info',
                        'sub' => 'warning',
                    }),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'main' => 'Main Only',
                        'sub' => 'Sub Only',
                    ]),
            ]);
    }
}