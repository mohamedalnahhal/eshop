<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayContract;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use App\Services\IconService;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Http\Middleware\InitializeTenancyForLivewire;
use App\Livewire\TenantAdmin\ThemeEditor;
use App\Services\Shipping\ShippingCalculatorService;
use App\Services\Checkout\CheckoutService;
use App\Http\Middleware\SetTenantLocale;
use App\Services\Payment;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Fix spatie/laravel-translation-loader cache tagging issue
        $this->app->extend('translation.loader', function ($loader, $app) {
            return $loader;
        });
        
        $this->app->singleton(ShippingCalculatorService::class);
        $this->app->singleton(CheckoutService::class);

        $this->app->bind(
            PaymentGatewayContract::class,
            Payment\Adapters\MockGatewayAdapter::class,
            Payment\Adapters\MockExpressGatewayAdapter::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/livewire/update', $handle)
                ->middleware([
                    'web',
                    InitializeTenancyForLivewire::class,
                    SetTenantLocale::class,
                ]);
        });

        FilamentColor::register([
            'primary'   => 'oklch(0.55 0.25 262.87)',
            'danger'    => Color::Red,
            'gray'      => Color::Slate,
            'info'      => Color::Blue,
            'success'   => Color::Emerald,
            'warning'   => Color::Orange,
        ]);
    }
}
