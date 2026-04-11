@php
    $localeService = app(\App\Services\TenantLocaleService::class);
    $supported = $localeService->getSupportedLocales();
    $current = app()->getLocale();

    $currentPath = request()->path();
    $segments = explode('/', $currentPath);
    if(count($segments) > 0 && in_array($segments[0], $supported)) {
        array_shift($segments);
    }
    $remainingPath = implode('/', $segments);
@endphp

@if(count($supported) > 1)
    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
        <button @click="open = !open"
                class="flex items-center gap-1 px-3 py-1.5 rounded-btn border border-border-input hover:bg-surface-100 transition-all text-theme-xs font-bold">
            <span>{{ strtoupper($current) }}</span>
            @icon('chevron-r', 'w-3 h-3 transition-transform rotate-90')
        </button>

        <div x-show="open"
             x-transition
             class="absolute top-full mt-1 end-0 bg-card-bg border border-border rounded-card shadow-modal z-50 min-w-24 overflow-hidden">
            @foreach($supported as $locale)
                <a href="{{ url($locale . ($remainingPath ? '/' . $remainingPath : '')) }}"
                   class="flex items-center gap-2 px-4 py-2 text-theme-xs font-bold hover:bg-surface-100 transition-colors
                          {{ $current === $locale ? 'text-primary bg-primary/5' : 'text-theme' }}">
                    {{ strtoupper($locale) }}
                    @if($current === $locale)
                        @icon('check', 'w-3 h-3 text-primary')
                    @endif
                </a>
            @endforeach
        </div>
    </div>
@endif