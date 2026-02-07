<?php

namespace App\Enums;

enum TenantStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BANNED = 'banned';
    case PENDING = 'pending';
    case MAINTENANCE = 'maintenance';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::BANNED => 'Banned',
            self::PENDING => 'Pending Approval',
            self::MAINTENANCE => 'Maintenance Mode',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'gray',
            self::BANNED => 'danger',
            self::PENDING => 'info',
            self::MAINTENANCE => 'warning',
        };
    }
}