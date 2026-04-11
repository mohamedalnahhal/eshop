<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use App\Services\IconService;
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
    $this->app->singleton(
        \App\Services\TenantLocaleService::class
    );

    // Fix spatie/laravel-translation-loader cache tagging issue
    $this->app->extend('translation.loader', function ($loader, $app) {
        return $loader;
    });
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

        Blade::directive('icon', function ($expression) {
            // @icon('cart')
            // @icon('cart', 'w-5 h-5')
            if (str_contains($expression, '$')) {
                return "<?php echo app(\App\Services\IconService::class)->render({$expression}); ?>";
            }
        
            $parts = str_getcsv($expression, ",");

            $name = trim($parts[0] ?? '', " '\"");
            $classes = trim($parts[1] ?? '', " '\"");

            return app(IconService::class)->render($name, $classes);
        });
    }
}
