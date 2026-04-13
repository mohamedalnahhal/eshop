<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TenantUserRole: int implements HasLabel
{
    case CUSTOMER = 0;
    case MANAGER = 20;
    case ADMIN = 99;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CUSTOMER => 'Customer',
            self::MANAGER => 'Manager',
            self::ADMIN => 'System Admin',
        };
    }
}