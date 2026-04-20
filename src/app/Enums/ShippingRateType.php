<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ShippingRateType: string implements HasLabel
{
    case FLAT_RATE = 'flat_rate';
    case FREE = 'free';
    case PRICE_BASED = 'price_based';
    case WEIGHT_BASED = 'weight_based';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::FLAT_RATE => 'Flat Rate',
            self::FREE => 'Free Shipping',
            self::PRICE_BASED => 'Price Based',
            self::WEIGHT_BASED => 'Weight Based',
        };
    }
}