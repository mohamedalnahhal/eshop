<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayContract;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Colors\Color;
use App\Services\Shipping\ShippingCalculatorService;
use App\Services\Checkout\CheckoutService;
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
        FilamentColor::register([
            'primary'   => Color::Blue,
            'danger'    => Color::Red,
            'gray'      => Color::Slate,
            'info'      => Color::Blue,
            'success'   => Color::Emerald,
            'warning'   => Color::Orange,
        ]);
    }
}
