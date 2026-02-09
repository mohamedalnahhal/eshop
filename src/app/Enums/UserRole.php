<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserRole: int implements HasLabel
{
    case CUSTOMER = 0;
    case ADMIN = 99;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CUSTOMER => 'customer',
            self::ADMIN => 'System Admin',
        };
    }
}