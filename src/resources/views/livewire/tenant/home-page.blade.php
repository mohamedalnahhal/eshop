<div class="min-h-screen tenant-body flex flex-col">
    
    {{-- Load fonts from Google --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', explode(',', $primaryFontFamily)[0]) }}&family={{ str_replace(' ', '+', explode(',', $secondaryFontFamily)[0]) }}&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-primary: {{ $primaryColor }};
            --color-secondary: {{ $secondaryColor }};
            --color-accent: {{ $accentColor }};
            --color-bg: {{ $backgroundColor }};
            --color-text: {{ $textColor }};
            --color-navbar: {{ $navbarColor }};
            --color-footer: {{ $footerColor }};
            --color-border: {{ $borderColor }};

            --font-primary: {{ $primaryFontFamily }};
            --font-secondary: {{ $secondaryFontFamily }};
            --font-size-base: {{ $baseFontSize }};
            --font-size-h1: {{ $h1FontSize }};
            --font-weight-primary: {{ $primaryFontWeight }};
            --font-weight-heading: {{ $headingFontWeight }};
            --font-line-height: {{ $lineHeight }};
            --font-letter-spacing: {{ $letterSpacing }};
        }

        .tenant-body {
            font-family: var(--font-primary);
            font-size: var(--font-size-base);
            font-weight: var(--font-weight-primary);
            color: var(--color-text);
            background-color: var(--color-bg);
            line-height: var(--font-line-height);
            letter-spacing: var(--font-letter-spacing);
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: var(--font-secondary);
            font-weight: var(--font-weight-heading);
        }

        .bg-navbar { background-color: var(--color-navbar); }
        .bg-footer { background-color: var(--color-footer); }
        .border-custom { border-color: var(--color-border); }
        .text-primary-custom { color: var(--color-primary); }
        .bg-primary-custom { background-color: var(--color-primary); }
        .bg-accent-custom { background-color: var(--color-accent); }
    </style>

    {{-- 1. Header --}}
    <header class="bg-navbar shadow-sm sticky top-0 z-50 border-b border-custom">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                @if($storeLogo)
                    <img src="{{ $storeLogo }}" alt="{{ $storeName }} Logo" class="h-10 w-auto object-contain">
                @endif
                <h1 class="text-2xl font-heading text-primary-custom" style="font-size: clamp(1.5rem, 5vw, var(--font-size-h1));">{{ $storeName }}</h1>
            </div>
            <nav>
                <a href="#" class="hover:text-[var(--color-primary)] transition-colors opacity-80 hover:opacity-100">Home</a>
            </nav>
        </div>
    </header>

    {{-- 2. Hero Section --}}
    <section class="bg-primary-custom text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl sm:text-5xl mb-4 font-heading" style="font-size: var(--font-size-h1);">Welcome to {{ $storeName }}</h2>
            <p class="text-lg sm:text-xl opacity-90 mb-8">{{ $storeSlogan }}</p>
            <button class="bg-white text-primary-custom px-8 py-3 font-heading shadow-lg hover:bg-gray-100 transition-all {{ $buttonShapeClass }}">
                Shop Now
            </button>
        </div>
    </section>

    {{-- 3. Products Grid Section --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 flex-grow">
        <h3 class="text-3xl mb-10 text-center font-heading">Featured Products</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @forelse ($products as $product)

                {{-- Use tenant specific $cardShapeClass --}}
                <div class="bg-[var(--color-navbar)] border-custom shadow-sm hover:shadow-md transition-shadow duration-300 {{ $cardShapeClass }} overflow-hidden flex flex-col">
                    
                    {{-- Product Image --}}
                    <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-100">
                        <img src="{{ $product->getFirstMediaUrl('default', 'thumb') ?: asset('images/placeholder.png') }}" 
                             alt="{{ $product->name }}" 
                             class="h-64 w-full object-cover object-center hover:scale-105 transition-transform duration-300">
                    </div>

                    {{-- Product Details --}}
                    <div class="p-5 flex flex-col flex-1">
                        <h4 class="text-lg font-heading mb-2 truncate">{{ $product->name }}</h4>
                        <p class="text-primary-custom font-bold mb-4 text-xl">
                            {{ number_format($product->price, 2) }} {{ $product->currency ?? 'SAR' }}
                        </p>
                        
                        <div class="mt-auto">

                            {{-- Add to Cart Button --}}
                            <button 
                                wire:click="addToCart({{ $product->id }})"
                                class="w-full bg-accent-custom hover:opacity-90 text-white py-2.5 px-4 transition-opacity flex items-center justify-center gap-2 {{ $buttonShapeClass }} font-heading">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-span-full text-center py-10 opacity-70">
                    No products found in this store yet.
                </div>
            @endforelse
        </div>

        {{-- 4. View All Products Button --}}
        @if($products->count() > 0)
            <div class="mt-12 text-center">
                <a href="" 
                   class="inline-block border-2 border-custom text-primary-custom hover:bg-primary-custom hover:text-white px-8 py-3 transition-colors {{ $buttonShapeClass }} font-heading">
                    View All Products
                </a>
            </div>
        @endif
    </main>

    {{-- 5. Footer --}}
    <footer class="bg-footer py-12 mt-auto" style="color: rgba(255,255,255,0.8);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <div>
                <h5 class="text-white text-lg font-heading mb-4">{{ $storeName }}</h5>
                <p class="text-sm">{{ $storeSlogan }}</p>
            </div>
            <div>
                <h5 class="text-white text-lg font-heading mb-4">Quick Links</h5>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-[var(--color-primary)] transition-colors">Home</a></li>
                    <li><a href="" class="hover:text-[var(--color-primary)] transition-colors">Products</a></li>
                </ul>
            </div>
            <div>
                <h5 class="text-white text-lg font-heading mb-4">Contact Us</h5>
                <p class="text-sm">Email: <a href="mailto:{{ $contactEmail }}" class="hover:text-[var(--color-primary)]">{{ $contactEmail }}</a></p>
                @if($contactPhone)
                    <p class="text-sm mt-2">Phone: <a href="tel:{{ $contactPhone }}" class="hover:text-[var(--color-primary)]">{{ $contactPhone }}</a></p>
                @endif
            </div>
        </div>
        <div class="text-center text-sm mt-12 pt-8 border-t" style="border-color: rgba(255,255,255,0.1);">
            &copy; {{ date('Y') }} {{ $storeName }}. All rights reserved.
        </div>
    </footer>
</div>