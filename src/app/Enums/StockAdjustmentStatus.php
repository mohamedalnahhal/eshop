<?php

namespace App\Enums;

enum StockAdjustmentStatus: string
{
    case ISSUED = 'issued';
    case WAITING = 'waiting';
    case DONE = 'done';

    public function label(): string
    {
        return match($this) {
            self::ISSUED => 'Issued',
            self::WAITING => 'Waiting',
            self::DONE => 'Done',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ISSUED => 'gray',
            self::WAITING => 'info',
            self::DONE => 'success',
        };
    }
}