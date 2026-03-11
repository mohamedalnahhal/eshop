<?php

namespace App\Filament\TenantAdmin\Resources\Locations\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class LocationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم الموقع')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'branch' => 'success',
                        'warehouse' => 'warning',
                        'pickup_point' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'branch' => 'فرع',
                        'warehouse' => 'مستودع',
                        'pickup_point' => 'نقطة استلام',
                        default => $state,
                    }),

                TextColumn::make('city')
                    ->label('المدينة')
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->copyable(),

                IconColumn::make('is_visible_to_customers')
                    ->label('مرئي للزبائن')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('تصفية حسب النوع')
                    ->options([
                        'branch' => 'فرع',
                        'warehouse' => 'مستودع',
                        'pickup_point' => 'نقطة استلام',
                    ]),
            ])
            ->actions([
                EditAction::make(), // تم التأكد من الاستيراد أعلاه
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}