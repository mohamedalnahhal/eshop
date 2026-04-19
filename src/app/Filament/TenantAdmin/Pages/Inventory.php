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
                    ->beforeStateUpdated(function ($record, $state) use (&$old) {
                        $old = $record->status;
                    })
                    ->afterStateUpdated(function ($record, $state) use (&$old) {
                        $status = StockAdjustmentStatus::tryFrom($state);
                        if ($status === StockAdjustmentStatus::DONE && $old !== StockAdjustmentStatus::DONE) {
                            $record->product->increment('stock', $record->updated_value);
                
                            Notification::make()
                                ->title('Stock Incremented')
                                ->success()
                                ->send();
                        } elseif ($old === StockAdjustmentStatus::DONE && $status !== StockAdjustmentStatus::DONE) {
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
                            ->nullable()
                            ->hidden(fn ($get) => $get('type') !== StockAdjustmentType::PURCHASE->value),
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