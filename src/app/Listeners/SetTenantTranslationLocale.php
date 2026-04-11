<?php

namespace App\Listeners;

use App\Services\TenantLocaleService;
use Stancl\Tenancy\Events\TenancyBootstrapped;

class SetTenantTranslationLocale
{
    public function handle(TenancyBootstrapped $event): void
    {
        $path = request()->path();
        if (str_starts_with($path, 'admin')) {
            app()->setLocale('en');
            return;
        }

        $service = app(TenantLocaleService::class);
        $defaultLocale = $service->getDefaultLocale();
        app()->setLocale($defaultLocale);
    }
}