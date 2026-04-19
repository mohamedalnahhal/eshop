<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class ApplyTenantTheme
{
    public function handle(Request $request, Closure $next)
    {
        if (tenant()) {
            $setting = tenant()->settings()->with('theme')->first();

            if ($setting && $setting->theme) {
                $theme = $setting->theme;

                // FilamentColor::register accepts an array like ['primary' => '#123456', 'danger' => '#ff0000']
                if (!empty($theme->palette)) {
                    FilamentColor::register($theme->palette);
                }

                // Since panel()->font() usually runs before tenancy, we can inject 
                // the font dynamically into the HTML head using a render hook.
                if (!empty($theme->font)) {
                    FilamentView::registerRenderHook(
                        PanelsRenderHook::HEAD_START,
                        fn (): string => Blade::render("
                            <style>
                                body { font-family: '{{ \$font }}', sans-serif !important; }
                            </style>
                        ", ['font' => $theme->font])
                    );
                }
            }
        }

        return $next($request);
    }
}