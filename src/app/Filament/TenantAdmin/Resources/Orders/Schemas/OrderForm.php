<?php

namespace App\Filament\TenantAdmin\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Models\User;
use App\Models\Product;
use App\Enums\AddressType;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship(
                        'user',
                        'username', 
                        // ignoring admin
                        modifyQueryUsing: fn (Builder $query) => $query->where('role', '<', 99)
                    )
                    ->required()
                    ->searchable()
                    ->live(onBlur: true)
                    ->native(false),
                    
                Select::make('shipping_address_id')
                    ->relationship(
                        name: 'shippingAddress',
                        titleAttribute: 'name',
                        // filter by the Morph constraints and address type
                        modifyQueryUsing: function (Builder $query, Get $get) {
                            return $query
                                ->where('addressable_type', User::class)
                                ->where('addressable_id', $get('user_id'))
                                ->where('type', AddressType::SHIPPING);
                        }
                    )
                    ->helperText(function (Get $get) {
                        $user_id = $get('user_id');
                        if (!$user_id) return null;
                        $user = User::with(['addresses' => fn($query) => $query->where('type', 'shipping')])
                            ->where('id', $user_id)
                            ->first();
                        if ($user->addresses->isEmpty()) {
                            return '⚠️ This user has no shipping addresses attached.';
                        }
                        return null;
                    })
                    ->disabled(fn (Get $get) => blank($get('user_id')))
                    ->required()
                    ->native(false),

                Select::make('status')
                    ->options(OrderStatus::class)
                    ->required()
                    ->native(false),

                // order items
                Repeater::make('items')
                    ->relationship()
                    ->table([
                        TableColumn::make('Product')->width('40%'),
                        TableColumn::make('Quantity'),
                        TableColumn::make('Unit Price'),
                    ])
                    ->compact()
                    ->schema([
                        Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if (! $state) {
                                    $set('unit_price', 0.00);
                                } else {
                                    $product = Product::find($state);
                                    $set('unit_price', $product?->price ?? 0.00);
                                }
                                self::updateRepeaterTotals($get, $set);
                            }),

                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->minValue(0)
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::updateRepeaterTotals($get, $set)),

                        TextInput::make('unit_price')
                            ->label('Unit Price')
                            ->numeric()
                            ->dehydrated(false)
                            ->readOnly() // fetched automatically
                            ->formatStateUsing(function (?Model $record, Get $get) {
                                if ($record && $record->product) {
                                    return $record->product->price;
                                }
                                $productId = $get('product_id');
                                if ($productId) {
                                    return Product::find($productId)?->price ?? 0;
                                }
                                return 0;
                            }),
                    ])
                    ->columns(3)
                    ->columnSpanFull()
                    ->live()
                    ->afterStateUpdated(fn (Get $get, Set $set) => self::updateTotals($get, $set)),

                TextInput::make('discount')
                    ->label('Discount Percentage')
                    ->suffix('%')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(0.0)
                    ->live()
                    ->afterStateUpdated(fn (Get $get, Set $set) => self::updateTotals($get, $set)),
                    
                TextInput::make('total_price')
                    ->numeric()
                    ->default(0.0)
                    ->readOnly(), // calculated automatically
                    
                TextInput::make('final_price')
                    ->numeric()
                    ->default(0.0)
                    ->readOnly(), // calculated automatically
            ]);
    }

    /**
     * Calculates totals when triggered from OUTSIDE the repeater (e.g., changing discount or deleting a row)
     */
    public static function updateTotals(Get $get, Set $set): void
    {
        $items = $get('items') ?? [];
        $totalPrice = 0;

        foreach ($items as $item) {
            $quantity = (float) ($item['quantity'] ?? 0);
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            $totalPrice += $quantity * $unitPrice;
        }

        $discount = (float) ($get('discount') ?? 0);
        $discountAmount = $totalPrice * ($discount / 100);
        $finalPrice = max(0, $totalPrice - $discountAmount);

        $set('total_price', $totalPrice);
        $set('final_price', $finalPrice);
    }

    /**
     * Calculates totals when triggered from INSIDE the repeater (e.g., changing quantity or product)
     * Needs '../../' to traverse up the state tree to the main form level.
     */
    public static function updateRepeaterTotals(Get $get, Set $set): void
    {
        $items = $get('../../items') ?? [];
        $totalPrice = 0;

        foreach ($items as $item) {
            $quantity = (float) ($item['quantity'] ?? 0);
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            $totalPrice += $quantity * $unitPrice;
        }

        $discount = (float) ($get('../../discount') ?? 0);
        $discountAmount = $totalPrice * ($discount / 100);
        $finalPrice = max(0, $totalPrice - $discountAmount);

        $set('../../total_price', $totalPrice);
        $set('../../final_price', $finalPrice);
    }
}