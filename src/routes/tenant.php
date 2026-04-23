<?php

declare(strict_types=1);

use App\Http\Middleware\SetTenantLocale;
use App\Http\Controllers\Auth\Customer\CustomerLoginController;
use App\Http\Controllers\Auth\Customer\CustomerRegisterController;
use App\Http\Controllers\Checkout\ExpressCheckoutController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    // Redirect root to default locale
    Route::get('/', function () {
        $service = app(\App\Services\TenantLocaleService::class);
        $locale = $service->getDefaultLocale() ?: 'en';
        return redirect("/{$locale}");
    });

    Route::prefix('{locale}')
        ->middleware(SetTenantLocale::class)
        ->group(function () {
            Route::livewire('/', 'pages::index')->name('shop.index');
            Route::livewire('/products', 'pages::products.index')->name('shop.products');
            Route::livewire('/product/{id}', 'pages::products.show')->name('shop.product.show');
            Route::livewire('/cart', 'pages::cart.index')->name('shop.cart');

            Route::middleware('guest:customer')->group(function () {
                Route::get('/login',  [CustomerLoginController::class, 'create'])->name('shop.login');
                Route::post('/login', [CustomerLoginController::class, 'store'])->name('shop.login.store');
                Route::get('/signup',  [CustomerRegisterController::class, 'create'])->name('shop.signup');
                Route::post('/signup', [CustomerRegisterController::class, 'store'])->name('shop.signup.store');
            });

            Route::middleware('auth:customer')->group(function () {
                Route::post('/logout', [CustomerLoginController::class, 'destroy'])->name('shop.logout');
                Route::livewire('/orders', 'pages::orders.index')->name('shop.orders');
                Route::livewire('/orders/{id}', 'pages::orders.show')->name('shop.order.show');

                // Route::get('/account', [AccountController::class, 'index'])->name('shop.account');
            });

            Route::livewire('/checkout', 'pages::checkout.index')->name('shop.checkout');
 
        });

    Route::get('/mock-psp/checkout/pay/{token}', function (string $token) {
        abort(501, 'Payment gateway not yet implemented.');
    })->name('checkout.pay');

    Route::prefix('checkout/express')
        ->middleware('throttle:60,1')
        ->controller(ExpressCheckoutController::class)
        ->group(function () {
            Route::post('/shipping-options', 'shippingOptions')
                ->name('checkout.express.shipping-options');

            Route::post('/confirm', 'confirm')
                ->name('checkout.express.confirm');
        });
});