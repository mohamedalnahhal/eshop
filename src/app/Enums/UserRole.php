<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserRole: int implements HasLabel
{
    case CUSTOMER = 0;
    case TENANT_OWNER = 20;
    case ADMIN = 99;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CUSTOMER => 'Customer',
            self::TENANT_OWNER => 'Tenant Owner',
            self::ADMIN => 'System Admin',
        };
    }
}