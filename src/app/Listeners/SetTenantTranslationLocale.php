<?php

namespace App\Listeners;

use App\Services\TenantLocaleService;
use Stancl\Tenancy\Events\TenancyBootstrapped;

class SetTenantTranslationLocale
{
    public function handle(TenancyBootstrapped $event): void
    {
        $service = app(TenantLocaleService::class);

        $defaultLocale = $service->getDefaultLocale();

        app()->setLocale($defaultLocale);
    }
}