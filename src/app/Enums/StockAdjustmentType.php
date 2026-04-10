<?php

namespace App\Enums;

enum StockAdjustmentType: string
{
    case PURCHASE = 'purchase'; 
    case PRODUCTION = 'production';
    case DAMAGED = 'damaged'; 

    public function label(): string
    {
        return match($this) {
            self::PURCHASE => 'Purchase',
            self::PRODUCTION => 'Production',
            self::DAMAGED => 'Damaged',
        };
    }
}