<?php

namespace App\Enums;

enum PaymentOwnerType: string
{
    case CHARGE = 'charge';
    case REFUND = 'refund';
}