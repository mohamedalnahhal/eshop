<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';

    public function label(): string {
        return ucfirst($this->value);
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::COMPLETED => 'success',
            self::FAILED => 'danger',
            self::REFUNDED => 'gray',
        };
    }
}