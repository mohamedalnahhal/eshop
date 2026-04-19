<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TenantLocaleService;
use Symfony\Component\HttpFoundation\Response;

class SetTenantLocale
{
    public function __construct(
        protected TenantLocaleService $localeService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        $supported = $this->localeService->getSupportedLocales();

        if (! $locale || ! in_array($locale, $supported)) {
            $locale = $this->localeService->getDefaultLocale();
        }

        app()->setLocale($locale);
        \Illuminate\Support\Facades\URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}