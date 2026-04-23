<?php

namespace App\Enums;

enum PaymentType: string
{
    case CHARGE = 'charge';
    case REFUND = 'refund';
}