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
        'minPrice' => ['as' => 'min'],
        'maxPrice' => ['as' => 'max'],
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
    ];

    public function mount()
    {
        $this->refreshPriceBounds(app(ProductService::class));
    }

    public function updatedMinPrice() { $this->resetPage(); }
    public function updatedMaxPrice() { $this->resetPage(); }
    public function updatedCategoryId() { 
        $this->resetPage();
        if(!$this->isEmpty) $this->refreshPriceBounds(app(ProductService::class));
    }

    public function refreshPriceBounds(ProductService $service) {
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
            'categories' => Category::orderBy('name')->get(),
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
            'المنتجات' => null,
    ]" />

    <div id="top-title-portal"></div>
    <div id="top-search-portal" class="sticky top-0 z-50"></div>
</x-slot>

<div>
    <template x-teleport="#header-search-portal">
        <div class="relative">
            <span class="absolute inset-s-3 top-3 opacity-50" wire:loading.remove wire:target="search">🔍</span>
    
            <div wire:loading wire:target="search" class="absolute inset-s-3 top-3.5 z-10">
                <x-spinner class="h-5 w-5 text-blue-600" stroke-width="3" />
            </div>

            <input 
                wire:model.live.debounce.400ms="search"
                type="text"
                class="w-full px-3 pr-10 py-3 bg-white border border-gray-300 shadow-sm shadow-gray-400/5 rounded-full focus:ring-4 focus:ring-blue-50 outline-none transition-all" 
                placeholder="عن ماذا تبحث ؟">
        </div>
    </template>

    <template x-teleport="#top-title-portal">
        <div class="container flex flex-row justify-between mb-2">
            <h1 class="text-2xl font-bold text-gray-600">تصفح جميع منتجاتنا</h1>
            <select
                wire:model.live="sortBy"
                wire:loading.attr="disabled"
                class="px-3 py-2 hidden sm:block bg-white border border-gray-100 rounded-lg focus:ring-4 focus:ring-blue-50 outline-none cursor-pointer shadow-sm appearance-none text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                <option value="latest">الأحدث</option>
                <option value="price_asc">السعر: من الأقل</option>
                <option value="price_desc">السعر: من الأعلى</option>
                <option value="top_rated">الأعلى تقييماً</option>
            </select>
        </div>
    </template>
    <template x-teleport="#top-search-portal">
        <div x-data="{ filtersOpen: false }" 
            @toggle-filters.window="filtersOpen = !filtersOpen"
            :class="filtersOpen ? 'bg-white' : 'bg-white/50 backdrop-blur-md'"
            class="py-4 w-full lg:hidden">
            <div class="container flex flex-row gap-4">
                <div class="relative grow">
                    <span class="absolute inset-s-3 top-3" wire:loading.remove wire:target="search">🔍</span>
                        
                        <div wire:loading wire:target="search" class="absolute inset-s-3 top-3.5 z-10">
                        <x-spinner class="h-5 w-5 text-blue-600" stroke-width="3" />
                    </div>

                    <input
                        wire:model.live.debounce.400ms="search"
                        type="text"
                        class="w-full px-3 pr-10 py-3 bg-white/50 backdrop-blur-md border border-gray-300 rounded-full focus:ring-4 focus:ring-blue-50 outline-none transition-all shadow-sm shadow-gray-400/10"
                        placeholder="عن ماذا تبحث ؟"
                    />
                </div>
                <button type="button"
                        @click="$dispatch('toggle-filters')"
                        class="flex items-center justify-center px-4 bg-white/50 backdrop-blur-md border border-gray-300 rounded-xl text-gray-600 focus:ring-4 focus:ring-blue-50 transition-all shadow-sm shadow-gray-400/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </button>
            </div>
        </div>
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start mt-4">
        <!-- filters -->
        <div x-data="{ showFilters: false }" 
             @toggle-filters.window="showFilters = !showFilters"
             class="lg:col-span-1 sticky top-20 lg:top-4 h-max z-10 transition-all duration-300 bg-white max-lg:pb-6 max-lg:-mx-4 max-lg:px-4"
             :class="showFilters ? 'block' : 'hidden lg:block'">
             <div class="flex flex-col gap-8">
                <div>
                    <label class="block text-xs font-black text-blue-900/40 uppercase mr-1 mb-2">الاقسام</label>
                    <select
                        wire:model.live="categoryId"
                        wire:loading.attr="disabled"
                        class="w-full px-3 py-2 bg-white border border-gray-100 rounded-lg focus:ring-4 focus:ring-blue-50 outline-none cursor-pointer shadow-sm appearance-none text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                        <option value="">كل المنتجات</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black text-blue-900/40 uppercase mr-1 mb-3">
                        السعر (ريال)
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
                            unit="ريال"
                        />
                    </div>
                </div>
                <div class="sm:hidden">
                    <label class="block text-xs font-black text-blue-900/40 uppercase mr-1 mb-2">ترتيب حسب</label>
                    <select
                        wire:model.live="sortBy"
                        wire:loading.attr="disabled" 
                        class="w-full px-3 py-2 bg-white border border-gray-100 rounded-lg focus:ring-4 focus:ring-blue-50 outline-none cursor-pointer shadow-sm appearance-none text-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                        <option value="latest">الأحدث</option>
                        <option value="price_asc">السعر: من الأقل</option>
                        <option value="price_desc">السعر: من الأعلى</option>
                        <option value="top_rated">الأعلى تقييماً</option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <x-primary-button
                        wire:click="clearFilters"
                        wire:loading.class="opacity-75 pointer-events-none"
                        wire:target="clearFilters"
                        class="grow">
                        <span wire:loading.remove wire:target="clearFilters">مسح الفلاتر</span>

                        <div wire:loading wire:target="clearFilters">
                            <span class="flex flex-row flex-nowrap items-center gap-2">
                                <x-spinner class="h-4 w-4" />
                                جاري المسح...
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
                        <div class="col-span-full text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                            <p class="text-2xl font-bold text-gray-400">لا توجد منتجات متوفرة حالياً.</p>
                        </div>
                    @endforelse
                </div>
            
                <div class="mt-12 flex justify-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>