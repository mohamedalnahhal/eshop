<?php

namespace App\Filament\TenantAdmin\Pages;

use App\Enums\StockAdjustmentStatus;
use App\Enums\StockAdjustmentType;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\Supplier;
use App\Filament\TenantAdmin\Widgets\InventoryOverview;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class Inventory extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.tenant-admin.resources.products.pages.manage-inventory';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Inventory';
    protected static ?int $navigationSort = 3;
    protected static ?string $title = 'Inventory Management';
    protected static string|\UnitEnum|null $navigationGroup = 'Products';


    public function table(Table $table): Table
    {
        return $table
            ->query(StockAdjustment::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge(),
                Tables\Columns\TextColumn::make('updated_value')
                    ->label('Qty')
                    ->badge(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->default('-'),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options(StockAdjustmentStatus::class)
                    ->beforeStateUpdated(function ($record, $state) {
                        $oldValue  = $record->status instanceof \BackedEnum ? $record->status->value : (string) $record->status;
                        $newValue  = $state instanceof \BackedEnum ? $state->value : (string) $state;
                        $typeValue = $record->type instanceof \BackedEnum ? $record->type->value : (string) $record->type;
                        $isDamaged = $typeValue === 'damaged';

                        $product   = $record->product;

                        if (! $product || $oldValue === $newValue) {
                            return;
                        }

                        if ($oldValue === 'done') {
                            $isDamaged
                                ? $product->increment('stock', $record->updated_value)
                                : $product->decrement('stock', $record->updated_value);
                        }

                        if ($newValue === 'done') {
                            $isDamaged
                                ? $product->decrement('stock', $record->updated_value)
                                : $product->increment('stock', $record->updated_value);
                        }
                    })
                    ->afterStateUpdated(function ($record, $state) {
                        Notification::make()
                            ->title('Status Updated')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->fillForm(fn (StockAdjustment $record) => [
                        'product_id'    => $record->product_id,
                        'type'          => $record->type->value,
                        'supplier_id'   => $record->supplier_id,
                        'updated_value' => $record->updated_value,
                        'status'        => $record->status->value,
                    ])
                    ->schema([
                        Select::make('product_id')
                            ->label('Product')
                            ->options(Product::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('type')
                            ->label('Type')
                            ->options(StockAdjustmentType::class)
                            ->required(),
                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->options(Supplier::pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),
                        TextInput::make('updated_value')
                            ->label('Quantity')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Select::make('status')
                            ->label('Status')
                            ->options(StockAdjustmentStatus::class)
                            ->required(),
                    ])
                    ->action(function (StockAdjustment $record, array $data) {
                        $oldStatusValue = $record->status instanceof \BackedEnum ? $record->status->value : (string) $record->status;
                        $newStatusValue = $data['status'] instanceof \BackedEnum ? $data['status']->value : (string) $data['status'];
                        $newTypeValue   = $data['type'] instanceof \BackedEnum ? $data['type']->value : (string) $data['type'];
                        $isDamaged      = $newTypeValue === 'damaged';
                        $oldQty         = $record->updated_value;
                        $newQty         = (int) $data['updated_value'];
                        $product        = $record->product;

                        if ($product && $oldStatusValue !== $newStatusValue) {
                            if ($oldStatusValue === 'done') {
                                $isDamaged
                                    ? $product->increment('stock', $oldQty)
                                    : $product->decrement('stock', $oldQty);
                            }
                            if ($newStatusValue === 'done') {
                                $isDamaged
                                    ? $product->decrement('stock', $newQty)
                                    : $product->increment('stock', $newQty);
                            }
                        }

                        $record->update([
                            'product_id'    => $data['product_id'],
                            'type'          => $data['type'],
                            'supplier_id'   => $data['supplier_id'] ?? null,
                            'updated_value' => $newQty,
                            'status'        => $data['status'],
                        ]);

                        Notification::make()
                            ->title('Request Updated Successfully')
                            ->success()
                            ->send();
                    }),
            ])
            ->filters([
                Tables\Filters\Filter::make('not_done')
                    ->label('Hide Completed')
                    ->query(fn ($query) => $query->where('status', '!=', StockAdjustmentStatus::DONE))
                    ->default(),
            ])
            ->headerActions([
                Action::make('create_adjustment')
                    ->label('New Request')
                    ->schema([
                        Select::make('product_id')
                            ->label('Product')
                            ->options(Product::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('type')
                            ->label('Type')
                            ->options(StockAdjustmentType::class)
                            ->required(),
                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->options(Supplier::pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),
                        TextInput::make('updated_value')
                            ->label('Quantity')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Select::make('status')
                            ->label('Status')
                            ->options(StockAdjustmentStatus::class)
                            ->default(StockAdjustmentStatus::ISSUED)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $statusValue = $data['status'] instanceof \BackedEnum ? $data['status']->value : (string) $data['status'];
                        $typeValue   = $data['type'] instanceof \BackedEnum ? $data['type']->value : (string) $data['type'];
                        $isDamaged   = $typeValue === 'damaged';
                        $qty         = (int) $data['updated_value'];

                        StockAdjustment::create([
                            'product_id'    => $data['product_id'],
                            'type'          => $data['type'],
                            'supplier_id'   => $data['supplier_id'] ?? null,
                            'updated_value' => $qty,
                            'status'        => $data['status'],
                        ]);

                        if ($statusValue === 'done') {
                            $product = Product::find($data['product_id']);
                            if ($product) {
                                $isDamaged
                                    ? $product->decrement('stock', $qty)
                                    : $product->increment('stock', $qty);
                            }
                        }

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
            InventoryOverview::class,
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        $low = Product::whereBetween('stock', [1, 10])->count();
        $out = Product::where('stock', '<=', 0)->count();
    
        return (string) ($low + $out);
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        if (Product::where('stock', '<=', 0)->exists()) {
            return 'danger';
        }
    
        if (Product::whereBetween('stock', [1, 10])->exists()) {
            return 'warning';
        }
    
        return 'primary';
    }
    
    public static function getNavigationBadgeTooltip(): ?string
    {
        $low = Product::whereBetween('stock', [1, 10])->count();
        $out = Product::where('stock', '<=', 0)->count();
    
        return "{$out} Out of Stock · {$low} Low Stock";
    }
}