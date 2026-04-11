<?php

namespace App\Services;

use Astrotomic\Translatable\Facades\Translatable;

class TenantLocaleService
{
    public function getSupportedLocales(): array
    {
        $tenant = tenant();

        if (! $tenant) {
            return config('translatable.locales', ['en']);
        }

        return $tenant->settings?->supported_languages
            ?? config('translatable.locales', ['en']);
    }

    public function getDefaultLocale(): string
    {
        $tenant = tenant();

        if (! $tenant) {
            return config('app.locale', 'en');
        }

        return $tenant->settings?->default_language
            ?? $tenant->settings?->language
            ?? config('app.locale', 'en');
    }

    public function getFallbackChain(): array
    {
        $default = $this->getDefaultLocale();
        $supported = $this->getSupportedLocales();
        $fallback = config('translatable.fallback_locale', 'en');

        $chain = [$default];

        if (! in_array($fallback, $chain)) {
            $chain[] = $fallback;
        }

        foreach ($supported as $locale) {
            if (! in_array($locale, $chain)) {
                $chain[] = $locale;
            }
        }

        return $chain;
    }
}