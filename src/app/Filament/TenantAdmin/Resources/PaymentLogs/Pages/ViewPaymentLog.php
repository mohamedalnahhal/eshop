<?php

namespace App\Filament\TenantAdmin\Resources\PaymentLogs\Pages;

use App\Filament\TenantAdmin\Resources\PaymentLogs\PaymentLogResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPaymentLog extends ViewRecord
{
    protected static string $resource = PaymentLogResource::class;
}