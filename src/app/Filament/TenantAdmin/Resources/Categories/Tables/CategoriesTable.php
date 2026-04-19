<?php
namespace App\Filament\TenantAdmin\Resources\Categories\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Grouping\Group;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('parent_id')
                ->label('Parent Category')
                ->titlePrefixedWithLabel(false)
                ->getTitleFromRecordUsing(fn ($record) => $record->parent?->name ?? 'Primary')
                ->orderQueryUsing(function (Builder $query, string $direction) {
                    $dir = $direction === 'desc' ? 'DESC' : 'ASC';
                    return $query->orderByRaw(
                        "(SELECT name FROM category_translations WHERE category_id = categories.id AND locale = ? LIMIT 1) {$dir}",
                        [app()->getLocale()]
                    );
                })
                ->collapsible(),
            ])
            ->columns([
                TextColumn::make('name')
                    ->label('Category Name')
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query->whereTranslationLike('name', "%{$search}%"))
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        $dir = $direction === 'desc' ? 'DESC' : 'ASC';
                        return $query->orderByRaw(
                            "(SELECT name FROM category_translations WHERE category_id = categories.id AND locale = ? LIMIT 1) {$dir}",
                            [app()->getLocale()]
                        );
                    }),
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
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'main' => 'Main Only',
                        'sub' => 'Sub Only',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                ActionGroup::make([
                    DeleteAction::make(),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ])
                ->label("Actions"),
            ]);
    }
}