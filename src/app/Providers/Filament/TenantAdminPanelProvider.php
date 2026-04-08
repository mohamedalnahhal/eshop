<?php

namespace App\Providers\Filament;

use App\Http\Middleware\ApplyTenantTheme;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class TenantAdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('tenant_admin')
            ->path('admin')
            ->login()
            ->profile()
            ->passwordReset()
            ->spa()
            ->favicon(fn () => tenant('logo_url') 
                ? asset('storage/' . tenant('logo_url')) 
                : asset('images/logo.svg')
            )
            ->brandLogo(fn () => view('filament.clusters.brand.tenant-logo'))
            ->brandName(fn () => tenant('name') ?? 'eShop Store')
            ->darkMode(false)
            ->discoverResources(in: app_path('Filament/TenantAdmin/Resources'), for: 'App\\Filament\\TenantAdmin\\Resources')
            ->discoverPages(in: app_path('Filament/TenantAdmin/Pages'), for: 'App\\Filament\\TenantAdmin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/TenantAdmin/Widgets'), for: 'App\\Filament\\TenantAdmin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,

                // --- Scope to tenant ---
                InitializeTenancyByDomain::class,
                PreventAccessFromCentralDomains::class,
                // ApplyTenantTheme::class,
                //------------------------

                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}