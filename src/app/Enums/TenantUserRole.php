<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TenantUserRole: int implements HasLabel
{
    case STAFF = 0;
    case MANAGER = 20;
    case OWNER = 99;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::STAFF => 'Staff',
            self::MANAGER => 'Manager',
            self::OWNER => 'Owner',
        };
    }
}