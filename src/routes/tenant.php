<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Middleware\SetTenantLocale;

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

    Route::prefix('{locale}')
        ->middleware(SetTenantLocale::class)
        ->group(function () {
            Route::livewire('/', 'pages::index')->name('shop.index');
            Route::livewire('/products', 'pages::products.index')->name('shop.products');
            Route::livewire('/product/{id}', 'pages::products.show')->name('shop.product.show');
            Route::livewire('/cart', 'pages::cart.index')->name('shop.cart');
        });

});