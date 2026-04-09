<?php

namespace App\Filament\TenantAdmin\Resources\Products\Pages;

use App\Models\Product;
use App\Filament\TenantAdmin\Resources\Products\ProductResource;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class ManageInventory extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    protected static string $resource = ProductResource::class;

    protected string $view = 'filament.tenant-admin.resources.products.pages.manage-inventory';

    public function getTitle(): string
    {
        return __('Stock Levels');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query())
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular()
                    ->label(__('Image')),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Product Name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('categories.name')
                    ->label(__('Category'))
                    ->badge(),
                Tables\Columns\TextColumn::make('stock')
                    ->label(__('Current Stock'))
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state <= 0  => 'danger',
                        $state <= 10 => 'warning',
                        default      => 'success',
                    }),
            ])
            ->actions([
                Action::make('update_stock')
                    ->label(__('Update'))
                    ->icon('heroicon-o-arrow-up-circle')
                    ->color('primary')
                    ->form([
                        Select::make('type')
                            ->label(__('Type'))
                            ->options([
                                'add'    => '⬆️ Add',
                                'remove' => '⬇️ Remove',
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
            ]);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\TenantAdmin\Resources\Products\Widgets\InventoryOverview::class,
        ];
    }
}