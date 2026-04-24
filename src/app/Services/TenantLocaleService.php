<?php

namespace App\Services;

class TenantLocaleService
{
    public function getSupportedLocales(): array
    {
        $tenant = tenant();

        if (! $tenant) {
            return config('translatable.locales', ['en']);
        }

        $supported = $tenant->settings?->supported_languages;

        if (! empty($supported)) {
            return $supported;
        }

        return [$tenant->settings?->default_language ?? 'en'];
    }

    public function getDefaultLocale(): string
    {
        $tenant = tenant();

        if (! $tenant) {
            return config('app.locale', 'en');
        }

        return $tenant->settings?->default_language
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

    public function resolveTranslation(array $translations): string
    {
        $locales = $this->getFallbackChain();
        foreach($locales as $locale){
            if (!empty($translations[$locale])) {
                return $translations[$locale];
            }
        }
        return (string) (array_values(array_filter($translations))[0] ?? '');
    }
}