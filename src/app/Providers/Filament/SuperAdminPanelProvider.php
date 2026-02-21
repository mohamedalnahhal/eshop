<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\HtmlString;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;

class SuperAdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {

        FilamentView::registerRenderHook(
            'panels::head.end',
            fn (): string => Blade::render('<style>
               
                .fi-sidebar-item-label, 
                .fi-sidebar-item-icon {
                    color: #000000 !important;
                    font-weight: 600 !important;
                }
                .fi-sidebar-item-icon:Hover {
                    color: #1E3A8A;
                }

                
                .fi-header-heading,
                .fi-breadcrumbs-item-label,
                .fi-breadcrumbs-item-label a {
                    color: #1E3A8A !important;
                }
                .fi-
                

                .fi-avatar, 
                .fi-user-avatar {
                    background-color: #1E3A8A !important;
                    color: #ffffff !important; /* لون الحرف أبيض */
                    border: 1px solid #1E3A8A !important;
                }
                .fi-avatar div {
                     background-color: transparent !important;
                }

                .fi-ta-header-cell-label, 
                th.fi-ta-header-cell span {
                    color: #000000 !important;
                    font-weight: bold !important;
                    font-size: 0.95rem !important;
                }

                .fi-btn-primary {
                    background-color: #1E3A8A !important;
                    border-color: #000000 !important;
                }
                .fi-btn-primary:hover {
                    background-color: #172e6e !important; /* لون أغمق قليلاً عند التمرير */
                }

                .fi-ta-icon-btn {
                    color: #1E3A8A !important;
                }
            </style>'),
        );

        return $panel
            ->default()
            ->id('super_admin')
            ->path('admin')
            ->domain(env('APP_URL'))
            ->login()
            ->registration()

       
            ->brandLogo(new HtmlString('
                <div style="display: flex !important; align-items: center !important; gap: 10px !important; flex-direction: row !important;">
                    <img src="' . asset('images/logo.svg') . '" alt="eShop" style="height: 35px; width: auto;">
                    <span style="font-size: 1.4rem; font-weight: bold; color: #1E3A8A; white-space: nowrap;">eShop</span>
                </div>
            '))

           
            ->colors([
                'primary'   => '#1E3A8A', // الأزرق الأساسي
                'danger'    => \Filament\Support\Colors\Color::Red,
                'gray'      => \Filament\Support\Colors\Color::Slate,
                'info'      => \Filament\Support\Colors\Color::Blue,
                'success'   => \Filament\Support\Colors\Color::Emerald,
                'warning'   => \Filament\Support\Colors\Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/SuperAdmin/Resources'), for: 'App\\Filament\\SuperAdmin\\Resources')
            ->discoverPages(in: app_path('Filament/SuperAdmin/Pages'), for: 'App\\Filament\\SuperAdmin\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/SuperAdmin/Widgets'), for: 'App\\Filament\\SuperAdmin\\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
