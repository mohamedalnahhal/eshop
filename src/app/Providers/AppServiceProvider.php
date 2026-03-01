<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Http\Middleware\InitializeTenancyForLivewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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
                ]);
        });

        FilamentView::registerRenderHook(
            'panels::head.end',
            fn (): string => Blade::render('@vite(\'resources/css/app.css\')'),
        );

        FilamentColor::register([
            'primary'   => 'oklch(0.43 0.15 261.58)',
            'danger'    => Color::Red,
            'gray'      => Color::Slate,
            'info'      => Color::Blue,
            'success'   => Color::Emerald,
            'warning'   => Color::Orange,
        ]);
    }
}
