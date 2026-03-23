<?php

namespace App\Filament\TenantAdmin\Resources\Orders\Pages;

use App\Filament\TenantAdmin\Resources\Orders\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
}
