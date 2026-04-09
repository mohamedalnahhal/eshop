<?php

namespace App\Filament\TenantAdmin\Resources\Products\Pages;

use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\Supplier;
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
        return 'Inventory Management';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(StockAdjustment::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'purchase'   => 'success',
                        'production' => 'info',
                        'damaged'    => 'danger',
                        default      => 'gray',
                    }),
                Tables\Columns\TextColumn::make('updated_value')
                    ->label('Qty')
                    ->badge(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->default('-'),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'issued'  => 'Issued',
                        'waiting' => 'Waiting',
                        'done'    => 'Done',
                    ])
                    ->beforeStateUpdated(function ($record, $state) use (&$old) {
                        $old = $record->status;
                    })
                    ->afterStateUpdated(function ($record, $state) use (&$old) {
                        if ($state === 'done' && $old !== 'done') {
                            $record->product->increment('stock', $record->updated_value);
                
                            Notification::make()
                                ->title('Stock Incremented')
                                ->success()
                                ->send();
                        } elseif ($old === 'done' && $state !== 'done') {
                            $record->product->decrement('stock', $record->updated_value);
                
                            Notification::make()
                                ->title('Stock Decremented')
                                ->warning()
                                ->send();
                        }
                    }),
            ])
            ->filters([
                Tables\Filters\Filter::make('not_done')
                    ->label('Hide Completed')
                    ->query(fn ($query) => $query->where('status', '!=', 'done'))
                    ->default(),
            ])
            ->headerActions([
                Action::make('create_adjustment')
                    ->label('New Request')
                    ->schema([
                        Select::make('product_id')
                            ->label('Product')
                            ->options(Product::pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('type')
                            ->label('Type')
                            ->options([
                                'purchase'   => 'Purchase',
                                'production' => 'Production',
                                'damaged'    => 'Damaged',
                            ])
                            ->required()
                            ->live(),
                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->options(Supplier::pluck('name', 'id'))
                            ->searchable()
                            ->nullable()
                            ->hidden(fn ($get) => $get('type') !== 'purchase'),
                        TextInput::make('updated_value')
                            ->label('Quantity')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'issued'  => 'Issued',
                                'waiting' => 'Waiting',
                                'done'    => 'Done',
                            ])
                            ->default('issued')
                            ->required(),
                    ])
                    ->action(function (array $data) {

                        StockAdjustment::create([
                            'product_id'    => $data['product_id'],
                            'type'          => $data['type'],
                            'supplier_id'   => $data['supplier_id'] ?? null,
                            'updated_value' => $data['updated_value'],
                            'status'        => $data['status'],
                        ]);

                        Notification::make()
                            ->title('Request Created Successfully')
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