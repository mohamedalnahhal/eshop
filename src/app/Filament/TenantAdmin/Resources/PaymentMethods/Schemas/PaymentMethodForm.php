<?php

namespace App\Filament\TenantAdmin\Resources\PaymentMethods\Schemas;

use App\Enums\PaymentProvider;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentMethodForm
{
    public static function configure(Schema $schema): Schema
    {
        $providerOptions = collect(PaymentProvider::cases())
            ->mapWithKeys(fn (PaymentProvider $p) => [$p->value => $p->label()])
            ->toArray();

        return $schema->components([

            Section::make('Gateway')
                ->icon('heroicon-o-credit-card')
                ->columns(2)
                ->schema([
                    Select::make('provider')
                        ->label('Payment Provider')
                        ->options($providerOptions)
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->columnSpanFull(),

                    TextInput::make('name')
                        ->label('Display Name')
                        ->placeholder('e.g. Credit Card, PayPal')
                        ->helperText('Shown to customers at checkout.')
                        ->required()
                        ->maxLength(100),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->helperText('Enable this method at checkout.')
                        ->default(false)
                        ->inline(false),
                ]),

            Section::make('Configuration')
                ->icon('heroicon-o-cog-6-tooth')
                ->description('Store gateway-specific settings such as API keys or credentials.')
                ->schema([
                    KeyValue::make('config')
                        ->label('Config')
                        ->keyLabel('Key')
                        ->valueLabel('Value')
                        ->reorderable()
                        ->columnSpanFull(),
                ]),

        ]);
    }
}
