@props(['header', 'palette'])
<header class="sm:pt-header-pt sm:pb-header-pb sm:mt-header-mt sm:mb-header-mb sm:[position:var(--header-position)] sm:top-header-st left-0 right-0 z-50 sm:rounded-header sm:border-b-header-bb sm:border-t-header-bt sm:border-l-header-bl sm:border-r-header-br sm:border-border-header sm:bg-header sm:backdrop-blur-header sm:shadow-header sm:text-on-header
           pt-m-header-pt pb-m-header-pb mt-m-header-mt mb-m-header-mb [position:var(--m-header-position)] top-m-header-st rounded-m-header border-b-m-header-bb border-t-m-header-bt border-l-m-header-bl border-r-m-header-br border-border-m-header bg-m-header backdrop-blur-m-header shadow-m-header text-on-m-header">
    <div class="flex flex-row gap-m-header-gap sm:gap-header-gap items-center justify-between
                sm:pl-header-content-pl sm:pr-header-content-pr px-4">

        {{-- Logo + Name --}}
        <div class="flex items-center gap-2">
            <div class="sm:w-header-logo-w sm:h-header-logo-h w-m-header-logo-w h-m-header-logo-h rounded-icon bg-primary/20 flex items-center justify-center shrink-0">
                @icon('image', 'w-5 h-5 text-primary opacity-60')
            </div>
            <span class="sm:text-header-title sm:font-header-title text-m-header-title font-m-header-title font-black text-nowrap">
                {{ tenant('name') }}
            </span>
        </div>

        {{-- Search --}}
        <div class="flex-1 max-w-xs mx-4 hidden sm:block">
            <div class="flex items-center gap-2 input header-input rounded-input-full!">
                @icon('search', 'w-4 h-4 text-muted shrink-0')
                <span class="text-sm text-muted">{{ __('Search...') }}</span>
            </div>
        </div>

        {{-- Icons --}}
        <div class="flex items-center gap-2">
            <div class="sm:w-header-icons-size sm:h-header-icons-size w-m-header-icons-size h-m-header-icons-size flex items-center justify-center rounded-icon bg-on-header/10">
                @icon('user', 'w-5 h-5')
            </div>
            <div class="sm:w-header-icons-size sm:h-header-icons-size w-m-header-icons-size h-m-header-icons-size flex items-center justify-center rounded-icon bg-on-header/10 relative">
                @icon('cart', 'w-5 h-5')
                <span class="absolute -top-1 -end-1 w-4 h-4 rounded-full bg-primary text-on-primary text-[10px] font-bold flex items-center justify-center">3</span>
            </div>
        </div>

    </div>
</header>