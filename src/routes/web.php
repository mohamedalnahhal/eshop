<?php

use App\Http\Controllers\Auth\Merchant\MerchantLoginController;
use App\Http\Controllers\Auth\Merchant\MerchantRegisterController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// Landing
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/eshop', [LandingController::class, 'index']);
Route::get('lang/{locale}', [LandingController::class, 'switchLang'])->name('lang.switch');

// Merchant auth (central domain)
    Route::get('/login',    [MerchantLoginController::class,   'create'])->name('merchant.login');
    Route::post('/login',   [MerchantLoginController::class,   'store'])->name('merchant.login.store');
    Route::get('/register', [MerchantRegisterController::class, 'create'])->name('merchant.register');
    Route::post('/register',[MerchantRegisterController::class, 'store'])->name('merchant.register.store');

