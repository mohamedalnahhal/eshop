<?php

use Illuminate\Support\Facades\Route;

Route::get('/mock-checkout1', function () {
    return view('mock-checkout1');
});
Route::get('/mock-checkout2', function () {
    return view('mock-checkout2');
});