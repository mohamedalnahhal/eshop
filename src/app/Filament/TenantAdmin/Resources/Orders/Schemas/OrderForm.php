<?php

namespace App\Filament\TenantAdmin\Resources\Orders\Schemas;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Address;
use App\Enums\AddressType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Money\MoneyService;
use App\Services\TenantLocaleService;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Tables\Columns\CheckboxColumn;
use Illuminate\Database\Eloquent\Model;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Radio::make('order_type')
                    ->label('Order for')
                    ->options([
                        'customer' => 'Registered Customer',
                        'guest'    => 'Guest',
                    ])
                    ->default('customer')
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(function (Set $set) {
                        $set('customer_id', null);
                        $set('shipping_address_id', null);
                        $set('use_custom_address', false);
                        $set('guest_name', null);
                        $set('guest_email', null);
                        $set('guest_phone', null);
                    })
                    ->inline(),

                Section::make()
                    ->compact()
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->required()
                            ->live(onBlur: true)
                            ->native(false)
                            ->afterStateUpdated(function (Set $set) {
                                $set('shipping_address_id', null);
                                $set('use_custom_address', false);
                            }),

                        Radio::make('use_custom_address')
                            ->label('Shipping address')
                            ->options([
                                false => 'Use saved address',
                                true  => 'Enter custom address',
                            ])
                            ->default(false)
                            ->live()
                            ->dehydrated(false)
                            ->afterStateUpdated(fn (Set $set) => $set('shipping_address_id', null))
                            ->visible(fn (Get $get) => filled($get('customer_id')))
                            ->inline(),

                        Select::make('shipping_address_id')
                            ->label('Saved address')
                            ->options(function (Get $get): array {
                                $customerId = $get('customer_id');
                                if (! $customerId) return [];

                                return Address::query()
                                    ->where('addressable_type', Customer::class)
                                    ->where('addressable_id', $customerId)
                                    ->where('type', AddressType::SHIPPING)
                                    ->get()
                                    ->mapWithKeys(fn ($a) => [
                                        $a->id => collect([
                                            $a->name,
                                            $a->line_1,
                                            $a->city,
                                        ])->filter()->implode(', '),
                                    ])
                                    ->toArray();
                            })
                            ->helperText(function (Get $get): ?string {
                                $customerId = $get('customer_id');
                                if (! $customerId) return null;

                                $hasAddresses = Address::query()
                                    ->where('addressable_type', Customer::class)
                                    ->where('addressable_id', $customerId)
                                    ->where('type', AddressType::SHIPPING)
                                    ->exists();

                                return $hasAddresses ? null : 'This customer has no shipping addresses.';
                            })
                            ->disabled(fn (Get $get) => blank($get('customer_id')))
                            ->native(false)
                            ->visible(fn (Get $get) => filled($get('customer_id')) && !$get('use_custom_address')),

                        Section::make('Custom shipping address')
                            ->compact()
                            ->columns(2)
                            ->schema(self::shippingAddressFields())
                            ->visible(fn (Get $get) => (bool) $get('use_custom_address')),
                    ])
                    ->columnSpanFull()
                    ->visible(fn (Get $get) => $get('order_type') === 'customer'),

                // guest fields
                Section::make()
                    ->compact()
                    ->schema([
                        Group::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('guest_name')
                                    ->label('Full name')
                                    ->required()
                                    ->maxLength(100),

                                TextInput::make('guest_email')
                                    ->label('Email')
                                    ->email()
                                    ->required(),

                                TextInput::make('guest_phone')
                                    ->label('Phone')
                                    ->tel()
                                    ->nullable(),
                            ])
                            ->columnSpan(1),

                        Section::make('Shipping address')
                            ->compact()
                            ->columns(2)
                            ->schema(self::shippingAddressFields()),
                    ])
                    ->columnSpanFull()
                    ->visible(fn (Get $get) => $get('order_type') === 'guest'),

                Repeater::make('items')
                    ->table([
                        TableColumn::make('Product')->width('40%'),
                        TableColumn::make('Quantity'),
                        TableColumn::make('Custom Price'),
                        TableColumn::make('Unit price'),
                    ])
                    ->compact()
                    ->schema([
                        Select::make('product_id')
                            ->label('Product')
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search): array {
                                return Product::whereTranslationLike('name', "%{$search}%")
                                    ->limit(50)
                                    ->get()
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelFromRecordUsing(fn (Product $record) => $record->name)
                            ->getOptionLabelUsing(function ($value): ?string {
                                if (str_starts_with((string) $value, '__name__:')) {
                                    $names = json_decode(substr($value, 9), true);
                                    if (is_array($names)) {
                                        return app(TenantLocaleService::class)->resolveTranslation($names);
                                    }
                                }
                            
                                $product = Product::find($value);
                                return $product?->name ?? (string) $value;
                            })
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state, MoneyService $moneyService) {
                                if (!$get('overwrite_price')) {
                                    $price = $state
                                        ? (Product::find($state)?->price ?? 0)
                                        : 0;
                                    $set('unit_price', $moneyService->fromMinor($price));
                                }
                                self::updateRepeaterTotals($get, $set, $moneyService);
                            }),

                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Get $get, Set $set, MoneyService $moneyService) => self::updateRepeaterTotals($get, $set, $moneyService)),
                        
                        Toggle::make('overwrite_price')
                            ->label('Custom price')
                            ->live()
                            ->extraFieldWrapperAttributes(['class' => 'items-center flex justify-center'])
                            ->afterStateUpdated(function (Get $get, Set $set, $state, MoneyService $moneyService) {
                                if (!$state) {
                                    // Reset to product's default price when toggled off
                                    $productId = $get('product_id');
                                    $price = $productId ? (Product::find($productId)?->price ?? 0) : 0;
                                    $set('unit_price', $moneyService->fromMinor($price));
                                }
                                self::updateRepeaterTotals($get, $set, $moneyService);
                            }),
                        
                        TextInput::make('unit_price')
                            ->label('Unit price')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->live(debounce: 500)
                            ->readOnly(fn (Get $get) => !$get('overwrite_price'))
                            ->afterStateUpdated(function (Get $get, Set $set, MoneyService $moneyService) {
                                self::updateRepeaterTotals($get, $set, $moneyService);
                            }),
                    ])
                    ->columns(4)
                    ->columnSpanFull()
                    ->live()
                    ->afterStateUpdated(fn (Get $get, Set $set, MoneyService $moneyService) => self::updateTotals($get, $set, $moneyService)),

                TextInput::make('shipping_fees')
                    ->label('Shipping fees')
                    ->numeric()
                    ->prefix(fn (?Order $record, MoneyService $moneyService): string =>
                        $moneyService::getSymbol($moneyService->resolveOrderCurrency($record))
                    )
                    ->default(0)
                    ->minValue(0)
                    ->live()
                    ->afterStateUpdated(fn (Get $get, Set $set, MoneyService $moneyService) => 
                        self::updateTotals($get, $set, $moneyService)
                    ),

                TextInput::make('discount')
                    ->label('Discount Amount')
                    ->numeric()
                    ->prefix(fn (?Order $record, MoneyService $moneyService): string =>
                        $moneyService::getSymbol($moneyService->resolveOrderCurrency($record))
                    )
                    ->minValue(0)
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(fn (Get $get, Set $set, MoneyService $moneyService) => 
                        self::updateTotals($get, $set, $moneyService)
                    ),

                Section::make('Computed')
                    ->compact()
                    ->columns(3)
                    ->schema([
                        TextInput::make('subtotal')
                            ->label('Items Total')
                            ->numeric()
                            ->prefix(fn (?Order $record, MoneyService $moneyService): string =>
                                $moneyService::getSymbol($moneyService->resolveOrderCurrency($record))
                            )
                            ->default(0)
                            ->readOnly()
                            ->dehydrated(true),
                        
                        TextInput::make('shipping_total')
                            ->label('Total With Shipping')
                            ->numeric()
                            ->prefix(fn (?Order $record, MoneyService $moneyService): string =>
                                $moneyService::getSymbol($moneyService->resolveOrderCurrency($record))
                            )
                            ->default(0)
                            ->readOnly()
                            ->dehydrated(false),
                        
                        TextInput::make('total')
                            ->label('Charged')
                            ->numeric()
                            ->prefix(fn (?Order $record, MoneyService $moneyService): string =>
                                $moneyService::getSymbol($moneyService->resolveOrderCurrency($record))
                            )
                            ->default(0)
                            ->readOnly()
                            ->dehydrated(true),
                    ])
                    ->columnSpanFull()
            ]);
    }

    private static function shippingAddressFields(): array
    {
        return [
            TextInput::make('shipping_address.name')
                ->label('Name / Label')
                ->required()
                ->columnSpanFull(),

            Select::make('shipping_address.country')
                ->label('Country')
                ->options(config('countries'))
                ->searchable()
                ->required()
                ->native(false),

            TextInput::make('shipping_address.city')
                ->label('City')
                ->required(),

            TextInput::make('shipping_address.postal_code')
                ->label('Postal code')
                ->nullable(),

            TextInput::make('shipping_address.state')
                ->label('State')
                ->nullable(),

            TextInput::make('shipping_address.line_1')
                ->label('Address line 1')
                ->required()
                ->columnSpanFull(),

            TextInput::make('shipping_address.line_2')
                ->label('Address line 2')
                ->nullable()
                ->columnSpanFull(),
        ];
    }
    
    /**
     * Calculates totals when triggered from OUTSIDE the repeater (e.g., changing discount or deleting a row)
     */
    public static function updateTotals(Get $get, Set $set, MoneyService $moneyService): void
    {
        $subtotal = self::calculateSubtotal($get('items') ?? [], $moneyService);
        $shippingFees = $get('shipping_fees');
        $shippingFees = $shippingFees? $moneyService->toMinor($shippingFees) : 0;
        $discount = $get('discount');
        $discount = $discount? $moneyService->toMinor($discount) : 0;

        [$shippingTotal, $total] = self::calculateTotals($subtotal, $shippingFees, $discount);

        $set('subtotal', $moneyService->format($subtotal));
        $set('shipping_total', $moneyService->format($shippingTotal));
        $set('total', $moneyService->format($total));
    }

    /**
     * Calculates totals when triggered from INSIDE the repeater (e.g., changing quantity or product)
     * Needs '../../' to traverse up the state tree to the main form level.
     */
    public static function updateRepeaterTotals(Get $get, Set $set, MoneyService $moneyService): void
    {
        $subtotal = self::calculateSubtotal($get('../../items') ?? [], $moneyService);
        $shippingFees = $get('../../shipping_fees');
        $shippingFees = $shippingFees? $moneyService->toMinor($shippingFees) : 0;
        $discount = $get('../../discount');
        $discount = $discount? $moneyService->toMinor($discount) : 0;

        [$shippingTotal, $total] = self::calculateTotals($subtotal, $shippingFees, $discount);

        $set('../../subtotal', $moneyService->format($subtotal));
        $set('../../shipping_total', $moneyService->format($shippingTotal));
        $set('../../total', $moneyService->format($total));
    }

    private static function calculateSubtotal(array $items, MoneyService $moneyService): int
    {
        $subtotal = 0.0;
        foreach ($items as $item) {
            $price = (float) ($item['unit_price'] ?? 0);
            $price = $moneyService->toMinor($price);
            $quantity = (int) ($item['quantity'] ?? 0);
            $subtotal += $price * $quantity;
        }
        return $subtotal;
    }

    private static function calculateTotals(int $subtotal, int $shippingFees, int $discount): array
    {
        $shippingTotal = $subtotal + $shippingFees;
        $total = max(0, $shippingTotal - $discount);
        return [$shippingTotal, $total];
    }
}