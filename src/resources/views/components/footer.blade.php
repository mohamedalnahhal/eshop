@php
    $theme    = tenant()->resolvedTheme();
    $fo       = $theme->resolvedFooter();
    $name     = tenant('name') ?? '';
    $logo     = tenant('logo_url');
    $slogan   = tenant()->settings?->slogan;
    $email    = tenant()->settings?->contact_email;
    $phone    = tenant()->settings?->contact_phone;

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

            @if($fo['show_logo'] || $fo['show_slogan'] || $fo['show_contact'])
                <div class="flex flex-col gap-4">

                    @if($fo['show_logo'])
                        <a href="{{ route('shop.index') }}" class="flex items-center gap-2 w-fit">
                            <img
                                src="{{ $logo ? asset('storage/'.$logo) : asset('images/logo.svg') }}"
                                class="w-footer-logo-w h-footer-logo-h object-contain"
                                alt="{{ $name }}"
                            >
                            <span class="font-black text-theme-xl text-on-footer">{{ $name }}</span>
                        </a>
                    @endif

                    @if($fo['show_slogan'] && $slogan)
                        <p class="text-theme-sm text-on-footer/70 max-w-xs leading-relaxed">
                            {{ $slogan }}
                        </p>
                    @endif

                    @if($fo['show_contact'] && $fo['columns'] < 3 && ($email || $phone))
                        <div class="flex flex-col gap-2 text-theme-sm text-on-footer/70">
                            @if($email)
                                <a href="mailto:{{ $email }}"
                                   class="flex items-center gap-2 hover:text-on-footer transition-colors">
                                    @icon('feathericon-mail', 'w-4 h-4 shrink-0')
                                    {{ $email }}
                                </a>
                            @endif
                            @if($phone)
                                <a href="tel:{{ $phone }}"
                                   class="flex items-center gap-2 hover:text-on-footer transition-colors">
                                    @icon('heroicon-o-phone', 'w-4 h-4 shrink-0')
                                    {{ $phone }}
                                </a>
                            @endif
                        </div>
                    @endif

                </div>
            @endif

            @if($fo['show_nav'] && !empty($fo['nav_links']))
                <div class="flex flex-col gap-4">
                    @if(!empty($fo['nav_title']))
                        <h3 class="font-bold text-theme-base text-on-footer">{{ __($fo['nav_title']) }}</h3>
                    @endif
                    <ul class="flex flex-col gap-2">
                        @foreach($fo['nav_links'] as $link)
                            @php
                                $href = Route::has($link['route'])
                                    ? route($link['route'], $link['params'] ?? [])
                                    : '#';
                            @endphp
                            <li>
                                <a href="{{ $href }}"
                                   wire:navigate
                                   class="text-theme-sm text-on-footer/70 hover:text-on-footer transition-colors flex items-center gap-1.5">
                                    @icon('chevron-r', 'w-3 h-3 rtl:rotate-180 opacity-50')
                                    {{ __($link['label']) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if((int)($fo['columns']) >= 3 && $fo['show_contact'] && ($email || $phone))
                <div class="flex flex-col gap-4">
                    @if(!empty($fo['contact_title']))
                        <h3 class="font-bold text-theme-base text-on-footer">{{ __($fo['contact_title']) }}</h3>
                    @endif
                    <div class="flex flex-col gap-3 text-theme-sm text-on-footer/70">
                        @if($email)
                            <a href="mailto:{{ $email }}"
                               class="flex items-center gap-2 hover:text-on-footer transition-colors">
                                @icon('feathericon-mail', 'w-4 h-4 shrink-0')
                                {{ $email }}
                            </a>
                        @endif
                        @if($phone)
                            <a href="tel:{{ $phone }}"
                               class="flex items-center gap-2 hover:text-on-footer transition-colors">
                                @icon('heroicon-o-phone', 'w-4 h-4 shrink-0')
                                {{ $phone }}
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        @if($fo['show_copyright'])
            <div class="border-t border-on-footer/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-theme-xs text-on-footer/50">
                <span>© {{ date('Y') }} {{ $name }} — {{ __($fo['copyright_text']) }}</span>
                <span class="flex items-center gap-1">
                    {{ __('Powered by') }}
                    <span class="text-on-footer/80 cursor-pointer hover:text-on-footer transition-colors flex items-center gap-1">
                        eShop with @icon('heroicon-o-heart', 'w-3.5 h-3.5')
                    </span>
                </span>
            </div>
        @endif

    </div>

</footer>