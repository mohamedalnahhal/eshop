<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/eshop', function () {

    $stats = [
        'tenants' => '1,200',
        'themes' => '45',
        'payments' => '8',
        'subscribers' => '+350'
    ];

    $isAr = app()->getLocale() === 'ar';
    $features = [
        [
            'icon' => '🎨',
            'title' => $isAr ? 'تصميم احترافي' : 'Professional Design',
            'description' => $isAr ? 'واجهات جاهزة للتخصيص.' : 'Ready to customize interfaces.',
            'badge' => $isAr ? 'جديد' : 'New',
            'bg' => '#f8fafc',
            'accent' => '#2563eb',
            'badge_bg' => '#dbeafe',
            'size' => 'md:col-span-2'
        ],
        [
            'icon' => '⚡',
            'title' => $isAr ? 'سرعة فائقة' : 'High Speed',
            'description' => $isAr ? 'أداء ممتاز لتحسين المبيعات.' : 'Excellent performance for sales.',
            'badge' => null,
            'bg' => '#f8fafc',
            'accent' => '#10b981',
            'badge_bg' => '#d1fae5',
            'size' => 'md:col-span-1'
        ],
    ];


    return view('landing.index', compact('stats', 'features'));
});
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');