<?php

namespace App\Filament\TenantAdmin\Resources\ShippingZones\Schemas;

use App\Enums\ShippingRateType;
use App\Services\Money\MoneyService;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class MethodsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Shipping Method')
                ->description('Define the method and its rate rules.')
                ->schema([
                    TextInput::make('name')
                        ->label('Method Name')
                        ->required()
                        ->maxLength(100),

                    TextInput::make('estimated_delivery')
                        ->label('Estimated Delivery Time')
                        ->maxLength(100),

                    TextInput::make('sort_order')
                        ->label('Sort Order')
                        ->numeric()
                        ->integer()
                        ->default(0)
                        ->minValue(0)
                        ->helperText('Lower numbers appear first to the customer.'),

                    Textarea::make('description')
                        ->label('Description')
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),

                    Repeater::make('rates')
                        ->relationship('rates')
                        ->label('Rate Rules')
                        ->addActionLabel('Add Rate Rule')
                        ->columns(2)
                        ->columnSpanFull()
                        ->collapsible()
                        ->reorderable('sort_order')
                        ->cloneable()
                        ->itemLabel(fn (array $state): string => ($state['rate_type']->getLabel() ?? 'Rate Rule'))
                        ->schema([
                            Select::make('rate_type')
                                ->label('Rate Type')
                                ->options(ShippingRateType::class)
                                ->required()
                                ->default(ShippingRateType::FLAT_RATE)
                                ->live()
                                ->columnSpanFull(),

                            TextInput::make('fee')
                                ->label('Shipping Fee')
                                ->numeric()
                                ->minValue(0)
                                ->required(fn (Get $get) => $get('rate_type') !== ShippingRateType::FREE)
                                ->hidden(fn (Get $get) => $get('rate_type') === ShippingRateType::FREE)
                                ->prefix(fn () => self::currencySymbol())
                                ->formatStateUsing(fn ($state) => blank($state) ? null : app(MoneyService::class)->fromMinor((int) $state))
                                ->dehydrateStateUsing(fn ($state) => blank($state) ? 0 : app(MoneyService::class)->toMinor((float) $state))
                                ->columnSpanFull(),

                            TextInput::make('free_above')
                                ->label('Free Shipping Above (optional)')
                                ->numeric()
                                ->minValue(0)
                                ->hidden(fn (Get $get) => $get('rate_type') === ShippingRateType::FREE)
                                ->helperText("Leave empty for no free threshold")
                                ->prefix(fn () => self::currencySymbol())
                                ->formatStateUsing(fn ($state) => blank($state) ? null : app(MoneyService::class)->fromMinor((int) $state))
                                ->dehydrateStateUsing(fn ($state) => blank($state) ? null : app(MoneyService::class)->toMinor((float) $state))
                                ->columnSpanFull(),

                            TextInput::make('condition_min')
                                ->label(fn (Get $get) => self::conditionLabel($get('rate_type'), 'min'))
                                ->helperText("Leave empty for no lower limit")
                                ->numeric()
                                ->minValue(0)
                                ->prefix(fn (Get $get) => self::conditionPrefix($get('rate_type')))
                                ->formatStateUsing(fn ($state, Get $get) => self::formatCondition($state, $get('rate_type')))
                                ->dehydrateStateUsing(fn ($state, Get $get) => self::dehydrateCondition($state, $get('rate_type'))),

                            TextInput::make('condition_max')
                                ->label(fn (Get $get) => self::conditionLabel($get('rate_type'), 'max'))
                                ->helperText("Leave empty for no upper limit")
                                ->numeric()
                                ->minValue(0)
                                ->prefix(fn (Get $get) => self::conditionPrefix($get('rate_type')))
                                ->formatStateUsing(fn ($state, Get $get) => self::formatCondition($state, $get('rate_type')))
                                ->dehydrateStateUsing(fn ($state, Get $get) => self::dehydrateCondition($state, $get('rate_type'))),
                        ]),
                ]),
        ]);
    }

    private static function conditionLabel(?ShippingRateType $rateType, string $side): string
    {
        if($side === 'min'){
            return match ($rateType) {
                ShippingRateType::WEIGHT_BASED => 'Min Weight (g)',
                default => 'Min Order Subtotal',
            };
        }
        else {
            return match ($rateType) {
                ShippingRateType::WEIGHT_BASED => 'Max Weight (g)',
                default => 'Max Order Subtotal',
            };
        }
    }

    private static function conditionPrefix(?ShippingRateType $rateType): ?string
    {
        if ($rateType === ShippingRateType::WEIGHT_BASED) {
            return 'g';
        }

        return self::currencySymbol();
    }

    private static function formatCondition(mixed $state, ?ShippingRateType $rateType): mixed
    {
        if (blank($state)) {
            return null;
        }

        if ($rateType === ShippingRateType::WEIGHT_BASED) {
            return (int) $state;
        }

        return app(MoneyService::class)->fromMinor((int) $state);
    }

    private static function dehydrateCondition(mixed $state, ?ShippingRateType $rateType): mixed
    {
        if (blank($state)) {
            return null;
        }

        if ($rateType === ShippingRateType::WEIGHT_BASED) {
            return (int) $state;
        }

        return app(MoneyService::class)->toMinor((float) $state);
    }

    private static function currencySymbol(): string
    {
        $code = tenant()?->settings?->currency ?? config('app.default_currency', 'USD');
        return MoneyService::getSymbol($code);
    }
}