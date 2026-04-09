<?php

namespace App\Filament\SuperAdmin\Resources\PaymentMethods\Pages;

use App\Filament\SuperAdmin\Resources\PaymentMethods\PaymentMethodResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentMethod extends CreateRecord
{
    protected static string $resource = PaymentMethodResource::class;
}