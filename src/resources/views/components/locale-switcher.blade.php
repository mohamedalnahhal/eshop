@php
    $localeService = app(\App\Services\TenantLocaleService::class);
    $supported = $localeService->getSupportedLocales();
    $current = app()->getLocale();
@endphp

@if(count($supported) > 1)
    <div class="locale-switcher">
        @foreach($supported as $locale)
            <a href="{{ url($locale . '/' . request()->path()) }}"
               class="{{ $current === $locale ? 'active' : '' }}">
                {{ strtoupper($locale) }}
            </a>
        @endforeach
    </div>
@endif