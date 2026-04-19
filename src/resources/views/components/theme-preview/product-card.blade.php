@props(['badge' => null, 'name' => null, 'price' => null, 'category' => null])
<div class="group flex flex-col card overflow-hidden transition-all duration-500 hover:-translate-y-2">

    {{-- Image --}}
    <div class="block relative overflow-hidden">
        <div class="aspect-square bg-surface-200 flex items-center justify-center">
            @icon('image', 'w-10 h-10 text-muted opacity-40')
        </div>
        @if($badge)
            <span class="absolute top-2 end-2 badge bg-primary text-on-primary text-xs">
                {{ $badge }}
            </span>
        @endif
    </div>

    {{-- Content --}}
    <div class="p-5 flex flex-col grow justify-between">
        <div class="flex flex-col">

            {{-- Name --}}
            <h2 class="line-clamp-1">
                <span class="block text-theme-xl font-bold mb-2 text-theme">
                    {{ $name ?? __('Product Name') }}
                </span>
            </h2>

            {{-- Stars --}}
            <div class="flex items-center gap-1 mb-4">
                @for($i = 0; $i < 5; $i++)
                    <span class="{{ $i < 4 ? 'text-gold' : 'text-muted' }} text-sm">★</span>
                @endfor
                <span class="text-muted text-theme-xs ms-1">(12)</span>
            </div>

            {{-- Category badge --}}
            @if($category)
                <div class="flex gap-2 mb-4">
                    <span class="badge bg-card-bg text-primary shadow-card border border-border">
                        {{ $category }}
                    </span>
                </div>
            @endif

            {{-- Price + Stock --}}
            <div class="mt-auto flex flex-col gap-3">
                <div class="flex flex-row flex-wrap items-center gap-3">
                    <span class="text-theme-2xl font-bold text-accent">
                        {{ $price ?? '99.00' }}
                    </span>
                    <div class="badge bg-success/10 text-success">
                        {{ __('In stock') }}
                    </div>
                </div>
                <p class="text-muted text-theme-sm mb-1">
                    {{ __('Product description goes here for preview purposes only.') }}
                </p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-2 pt-4 border-t border-border">
            <button class="btn flex-1 text-center bg-surface-200 hover:bg-surface-300 text-theme text-theme-sm">
                {{ __('View') }}
            </button>
            <button class="btn btn-primary grow-0 overflow-hidden text-theme-sm px-3 py-2">
                {{ __('Add to cart') }}
                @icon('cart', 'w-4 h-4')
            </button>
        </div>
    </div>
</div>