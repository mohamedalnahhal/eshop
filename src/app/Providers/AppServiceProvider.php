<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use App\Services\IconService;

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
