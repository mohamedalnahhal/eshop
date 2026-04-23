<?php

namespace App\Filament\TenantAdmin\Resources\PaymentMethods\Pages;

use App\Filament\TenantAdmin\Resources\PaymentMethods\PaymentMethodResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentMethod extends CreateRecord
{
    protected static string $resource = PaymentMethodResource::class;
}
