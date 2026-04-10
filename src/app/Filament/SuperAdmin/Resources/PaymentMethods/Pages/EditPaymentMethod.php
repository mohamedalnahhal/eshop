<?php

namespace App\Filament\SuperAdmin\Resources\PaymentMethods\Pages;

use App\Filament\SuperAdmin\Resources\PaymentMethods\PaymentMethodResource;
use Filament\Resources\Pages\EditRecord;

class EditPaymentMethod extends EditRecord
{
    protected static string $resource = PaymentMethodResource::class;
}