<?php

namespace App\Filament\SuperAdmin\Resources\PaymentMethodResource\Pages;

use App\Filament\SuperAdmin\Resources\PaymentMethodResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentMethod extends CreateRecord
{
    protected static string $resource = PaymentMethodResource::class;
}