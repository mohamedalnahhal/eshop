<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Customer\ProductsController;

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
    Route::livewire('/', 'pages::index')->name('shop.index');

    Route::livewire('/products', 'pages::products.index')->name('shop.products');
    Route::livewire('/product/{id}', 'pages::products.show')->name('shop.product.show');
    Route::post('/product/{id}/review', [ProductsController::class, 'storeReview'])->name('shop.product.review.store');

    Route::livewire('/cart', 'pages::cart.index')->name('shop.cart');
});