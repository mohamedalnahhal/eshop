<?php

namespace App\Filament\SuperAdmin\Resources\TenantSubscriptions\Pages;

use App\Filament\SuperAdmin\Resources\TenantSubscriptions\TenantSubscriptionResource;
use Filament\Resources\Pages\EditRecord;

class EditTenantSubscription extends EditRecord
{
    protected static string $resource = TenantSubscriptionResource::class;
}
