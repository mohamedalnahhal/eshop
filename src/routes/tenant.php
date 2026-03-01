<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Customer\ProductsController;
use App\Http\Controllers\Customer\CartController;

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
    
    Route::get('/shop', [ProductsController::class, 'index'])->name('shop.index');
    Route::get('/shop/product/{id}', [ProductsController::class, 'show'])->name('shop.product.show');
    Route::post('/shop/product/{id}/review', [ProductsController::class, 'storeReview'])->name('shop.product.review.store');

    Route::get('/shop/cart', [CartController::class, 'index'])->name('shop.cart.index');
    Route::post('/shop/cart/add/{id}', [CartController::class, 'add'])->name('shop.cart.add');

    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });

}); // إغلاق مجموعة المسارات بشكل صحيح