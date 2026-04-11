<?php

namespace App\Listeners;

use Stancl\Tenancy\Events\TenancyBootstrapped;

class LoadTenantTheme
{
    public function handle(TenancyBootstrapped $event): void
    {
        $event->tenancy->tenant->load('settings.theme');
    }
}