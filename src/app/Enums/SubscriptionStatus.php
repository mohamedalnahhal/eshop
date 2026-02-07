<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case ACTIVE = 'active';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
    case TRIALING = 'trialing';
    case PENDING = 'pending';
    case PAST_DUE = 'past_due';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::CANCELLED => 'Cancelled',
            self::EXPIRED => 'Expired',
            self::TRIALING => 'On Trial',
            self::PENDING => 'Pending',
            self::PAST_DUE => 'Past Due',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ACTIVE => 'success',
            self::CANCELLED => 'danger',
            self::EXPIRED => 'warning',
            self::TRIALING => 'info',
            self::PENDING => 'gray',
            self::PAST_DUE => 'danger',
        };
    }
}