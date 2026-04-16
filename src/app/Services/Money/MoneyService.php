<?php

namespace App\Services\Money;

use App\Models\Order;

class MoneyService
{
    public function toMinor(float|int $amount): int
    {
        $decimals = tenant()->settings?->currency_decimals ?? 2;
        $multiplier = pow(10, $decimals);

        return (int) bcmul((string) $amount, (string) $multiplier, 0);
    }

    public function fromMinor(int $amount): float
    {
        $decimals = tenant()->settings?->currency_decimals ?? 2;
        return $amount / pow(10, $decimals);
    }

    public function format(int $amount): string
    {
        $code = tenant()->settings?->currency ?? config('app.default_currency');
        $symbol = $this::getSymbol($code);

        $value = $this->fromMinor($amount);

        $currency_theme = tenant()->resolvedTheme()->resolvedCurrency();

        return $currency_theme['position'] === 'before'
            ? $symbol . $value
            : $value . ' ' . $symbol;
    }

    public function formatOrderPrice(Order $order, int $amount): string
    {
        $order_currency = $this->resolveOrderCurrency($order);
        $tenant_currency = tenant()->settings?->currency ?? config('app.default_currency');

        $symbol = $this::getSymbol($order_currency);
        $value = $this->fromMinor($amount);

        if($order_currency === $tenant_currency){
            $currency_theme = tenant()->resolvedTheme()->resolvedCurrency();
            return $currency_theme['position'] === 'before'
                ? $symbol . $value
                : $value . ' ' . $symbol;
        }

        // default formating not based on tenant currency theme formating
        return $symbol . $value;
    }

    public function resolveOrderCurrency(?Order $record): string
    {
        $code = config('app.default_currency');

        if (!$record) $code = tenant()->settings?->currency ?? $code;
        else $code = $record->currency;

        return $code;
    }

    public function resolveCurrency(): string
    {
        return tenant()->settings?->currency ?? config('app.default_currency');
    }

    public static function getSymbol(string $currencyCode)
    {
        $formatter = new \NumberFormatter("en_US@currency={$currencyCode}", \NumberFormatter::CURRENCY);
        return $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
    }
}