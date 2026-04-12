<?php

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\ProductService;
use Livewire\Attributes\Computed;

new class extends Component
{
    use WithPagination;

    public ?string $categoryId = null;
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    public string $search = '';
    public string $sortBy = 'latest';

    public int $boundMin = 0;
    public int $boundMax = 10000;

    protected $queryString = [
        'categoryId' => ['as' => 'category'],
        'minPrice'   => ['as' => 'min'],
        'maxPrice'   => ['as' => 'max'],
        'search'     => ['except' => ''],
        'sortBy'     => ['except' => 'latest'],
    ];

    public function mount()
    {
        $this->refreshPriceBounds(app(ProductService::class));
    }

    public function updatedMinPrice() { $this->resetPage(); }
    public function updatedMaxPrice() { $this->resetPage(); }
    public function updatedCategoryId()
    {
        $this->resetPage();
        if (!$this->isEmpty) $this->refreshPriceBounds(app(ProductService::class));
    }

    public function refreshPriceBounds(ProductService $service)
    {
        $range = $service->getPriceRange($this->categoryId);

        $this->boundMin = $range['min'] ?? 0;
        $this->boundMax = $range['max'] ?? 10000;

        $this->minPrice = max($this->boundMin, min($this->minPrice ?? $this->boundMin, $this->boundMax));
        $this->maxPrice = min($this->boundMax, max($this->maxPrice ?? $this->boundMax, $this->boundMin));
    }

    public function clearFilters()
    {
        $this->reset(['categoryId', 'search', 'sortBy']);
        $this->minPrice = $this->boundMin;
        $this->maxPrice = $this->boundMax;
        $this->resetPage();
    }

    #[Computed]
    public function products()
    {
        return app(ProductService::class)->getFiltered([
            'category_id' => $this->categoryId,
            'min_price'   => $this->minPrice,
            'max_price'   => $this->maxPrice,
            'search'      => $this->search,
            'sort'        => $this->sortBy,
        ]);
    }

    #[Computed]
    public function isEmpty()
    {
        return $this->products->isEmpty();
    }

    public function render()
    {
        return view('pages.products.index', [
            'products'   => $this->products,
            'categories' => Category::with('translations')->orderBy('name')->get(),
            'isEmpty'    => $this->products->isEmpty(),
        ]);
    }
};
?>

<x-slot name="header">
    <div class="grow hidden lg:block" id="header-search-portal"></div>
</x-slot>

<x-slot name="top">
    <x-breadcrumbs :links="[
        __('Products') => null,
    ]" />
    <div id="top-title-portal"></div>
    <div id="top-search-portal"></div>
</x-slot>

<div>
    <template x-teleport="#header-search-portal">
        <div class="relative">
            <span class="absolute top-1/2 -translate-y-1/2 sm:inset-s-header-search-px inset-s-m-header-search-px z-10 pointer-events-none" wire:loading.remove wire:target="search">
                @icon('search', 'w-5 h-5 sm:text-on-header/50 text-on-m-header/50')
            </span>
            <div wire:loading wire:target="search" class="absolute top-1/2 -translate-y-1/2 sm:inset-s-header-search-px inset-s-m-header-search-px z-10 pointer-events-none">
                <x-spinner class="h-5 w-5 sm:text-on-header text-on-m-header" stroke-width="2" />
            </div>
            <input
                wire:model.live.debounce.400ms="search"
                type="text"
                class="input header-input w-full sm:pr-[calc(var(--spacing-header-search-px)+2rem)]! pr-[calc(var(--spacing-m-header-search-px)+2rem)]! rounded-input-full!"
                placeholder="{{ __('What are you looking for?') }}">
        </div>
    </template>

    <template x-teleport="#top-title-portal">
        <div class="theme-container flex flex-row justify-between mb-2">
            <h1 class="text-theme-2xl font-bold text-theme">{{ __('Browse all our products') }}</h1>
            <select
                wire:model.live="sortBy"
                wire:loading.attr="disabled"
                class="input hidden sm:block w-auto text-muted cursor-pointer appearance-none disabled:opacity-50 disabled:cursor-not-allowed">
                <option value="latest">{{ __('Latest') }}</option>
                <option value="price_asc">{{ __('Price: Low to High') }}</option>
                <option value="price_desc">{{ __('Price: High to Low') }}</option>
                <option value="top_rated">{{ __('Top Rated') }}</option>
            </select>
        </div>
    </template>

    <template x-teleport="#top-search-portal">
        <div x-data="{ filtersOpen: false }"
            @toggle-filters.window="filtersOpen = !filtersOpen"
            :class="filtersOpen ? 'border-b-none' : 'border-b border-border-muted'"
            class="bg-bg py-4 w-full lg:hidden">
            <div class="theme-container flex flex-row gap-4">
                <div class="relative grow">
                    <span class="absolute top-1/2 -translate-y-1/2 inset-s-header-search-px z-10 pointer-events-none" wire:loading.remove wire:target="search">
                        @icon('search', 'w-5 h-5 text-muted')
                    </span>
                    <div wire:loading wire:target="search" class="absolute top-1/2 -translate-y-1/2 inset-s-header-search-px z-10 pointer-events-none">
                        <x-spinner class="h-5 w-5 text-primary" stroke-width="2" />
                    </div>
                    <input
                        wire:model.live.debounce.400ms="search"
                        type="text"
                        class="input header-input shadow-input! border-border-input! w-full pr-[calc(var(--spacing-m-header-search-px)+2rem)]! rounded-input-full! bg-surface-100/50 backdrop-blur-md"
                        placeholder="{{ __('What are you looking for?') }}"
                    />
                </div>
                <button type="button"
                        @click="$dispatch('toggle-filters')"
                        class="flex items-center justify-center py-m-header-search-py px-btn-x bg-surface-100/50 backdrop-blur-md border border-border-input rounded-input text-theme focus:outline-4 focus:outline-primary/10 transition-all shadow-input">
                    @icon('filter', 'h-5 w-5')
                </button>
            </div>
        </div>
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start mt-4">
        <div x-data="{ showFilters: false }"
             @toggle-filters.window="showFilters = !showFilters"
             class="lg:col-span-1 lg:sticky lg:top-header-hm h-max z-10 transition-all duration-300 bg-bg max-lg:pb-6 max-lg:-mx-4 max-lg:px-4"
             :class="showFilters ? 'block border-b border-border-muted' : 'hidden lg:block'">
             <div class="flex flex-col gap-8">
                <div>
                    <label class="block text-theme-xs font-black text-muted uppercase mr-1 mb-2">{{ __('Categories') }}</label>
                    <select
                        wire:model.live="categoryId"
                        wire:loading.attr="disabled"
                        class="input w-full text-muted cursor-pointer appearance-none disabled:opacity-50 disabled:cursor-not-allowed">
                        <option value="">{{ __('All Products') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-theme-xs font-black text-muted uppercase mr-1 mb-3">
                        {{ __('Price') }} ({{ tenant()->settings?->currency ?? 'USD' }})
                    </label>
                    <div
                        wire:loading.class="opacity-50 pointer-events-none"
                        wire:key="slider-{{ $categoryId ?? 'all' }}-{{ $minPrice }}-{{ $maxPrice }}-{{ $boundMin }}-{{ $boundMax }}"
                        @class(['opacity-40 pointer-events-none grayscale' => $isEmpty, 'transition-all duration-300 px-4' => true])>
                        <x-range-slider
                            :bound-min="$boundMin"
                            :bound-max="$boundMax"
                            :from-value="$minPrice ?? $boundMin"
                            :to-value="$maxPrice ?? $boundMax"
                            :step="1"
                            from-model="minPrice"
                            to-model="maxPrice"
                            :unit="tenant()->settings?->currency ?? 'USD'"
                        />
                    </div>
                </div>
                <div class="sm:hidden">
                    <label class="block text-theme-xs font-black text-muted uppercase mr-1 mb-2">{{ __('Sort by') }}</label>
                    <select
                        wire:model.live="sortBy"
                        wire:loading.attr="disabled"
                        class="input w-full text-muted cursor-pointer appearance-none disabled:opacity-50 disabled:cursor-not-allowed">
                        <option value="latest">{{ __('Latest') }}</option>
                        <option value="price_asc">{{ __('Price: Low to High') }}</option>
                        <option value="price_desc">{{ __('Price: High to Low') }}</option>
                        <option value="top_rated">{{ __('Top Rated') }}</option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <x-primary-button
                        wire:click="clearFilters"
                        wire:loading.class="opacity-75 pointer-events-none"
                        wire:target="clearFilters"
                        class="grow">
                        <span wire:loading.remove wire:target="clearFilters">{{ __('Clear Filters') }}</span>
                        <div wire:loading wire:target="clearFilters">
                            <span class="flex flex-row flex-nowrap items-center gap-2">
                                <x-spinner class="h-4 w-4" />
                                {{ __('Loading...') }}
                            </span>
                        </div>
                    </x-primary-button>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 flex flex-col">
            <div wire:loading.delay class="w-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @for ($i = 0; $i < 6; $i++)
                        <x-listing-product-skeleton />
                    @endfor
                </div>
            </div>

            <div wire:loading.remove.delay>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($products as $product)
                        <livewire:listing-product :product="$product" :key="'product-'.$product->id.'-'.request('page', 1)" />
                    @empty
                        <div class="col-span-full text-center py-20 rounded-card border-2 border-dashed border-border">
                            <p class="text-theme-2xl font-bold text-muted">{{ __('No products available') }}</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-12 flex justify-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>