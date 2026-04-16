<?php

namespace App\Enums;

enum PaymentOwnerType: string
{
    case TENANT = 'tenant';
    case PLATFORM = 'platform';
}