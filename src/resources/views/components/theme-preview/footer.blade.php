@props(['footer' => []])
@php
    $fo   = $footer;
    $name = tenant('name') ?? '';
    $cols = match((int)($fo['columns'] ?? 3)) {
        1       => 'grid-cols-1',
        2       => 'grid-cols-1 sm:grid-cols-2',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    };
@endphp

<footer class="bg-footer text-on-footer
               pt-footer-pt pb-footer-pb mt-footer-mt
               border-t-footer-bt border-b-footer-bb border-border-m-header sm:border-border-header">
    <div class="theme-container">
        <div class="{{ $cols }} gap-10 grid mb-10">

            @if($fo['show_logo'] ?? true)
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-2 w-fit">
                        <div class="w-footer-logo-w h-footer-logo-h rounded-icon bg-on-footer/20 flex items-center justify-center">
                            @icon('image', 'w-5 h-5 opacity-50')
                        </div>
                        <span class="font-black text-theme-xl text-on-footer">{{ $name }}</span>
                    </div>
                    @if($fo['show_slogan'] ?? true)
                        <p class="text-theme-sm text-on-footer/70 max-w-xs leading-relaxed">
                            {{ tenant()->settings?->slogan ?? __('Your trusted online store') }}
                        </p>
                    @endif
                </div>
            @endif

            @if($fo['show_nav'] ?? true)
                <div class="flex flex-col gap-4">
                    <h3 class="font-bold text-theme-base text-on-footer">
                        {{ $fo['nav_title'] ?? __('Quick Links') }}
                    </h3>
                    <ul class="flex flex-col gap-2">
                        @foreach($fo['nav_links'] ?? [] as $link)
                            <li>
                                <span class="text-theme-sm text-on-footer/70 flex items-center gap-1.5">
                                    @icon('chevron-r', 'w-3 h-3 rtl:rotate-180 opacity-50')
                                    {{ $link['label'] }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if((int)($fo['columns'] ?? 3) >= 3 && ($fo['show_contact'] ?? true))
                <div class="flex flex-col gap-4">
                    <h3 class="font-bold text-theme-base text-on-footer">
                        {{ $fo['contact_title'] ?? __('Contact Us') }}
                    </h3>
                    <div class="flex flex-col gap-3 text-theme-sm text-on-footer/70">
                        <span class="flex items-center gap-2">
                            @icon('mail', 'w-4 h-4 shrink-0')
                            {{ tenant()->settings?->contact_email ?? 'info@store.com' }}
                        </span>
                        <span class="flex items-center gap-2">
                            @icon('phone', 'w-4 h-4 shrink-0')
                            {{ tenant()->settings?->contact_phone ?? '+1 234 567 890' }}
                        </span>
                    </div>
                </div>
            @endif

        </div>

        @if($fo['show_copyright'] ?? true)
            <div class="border-t border-on-footer/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-theme-xs text-on-footer/50">
                <span>© {{ date('Y') }} {{ $name }} — {{ $fo['copyright_text'] ?? __('All rights reserved') }}</span>
            </div>
        @endif
    </div>
</footer>