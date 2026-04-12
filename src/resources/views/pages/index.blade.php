<?php

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use App\Services\ProductService;
use Livewire\Attributes\Computed;

new class extends Component
{
    #[Computed]
    public function theme()
    {
        return tenant()->resolvedTheme();
    }
    
    #[Computed]
    public function sections()
    {
        return $this->theme->homepageSections()->filter(fn($s) => $s['enabled']);
    }
    
    #[Computed]
    public function keys()
    {
        return $this->sections->pluck('key');
    }
    
    #[Computed]
    public function featuredCategories()
    {
        if (!$this->keys->contains('categories')) return collect();
    
        return Category::whereNull('parent_id')
            ->with([
                'translations',
                'children' => fn($q) => $q->withCount('products')->with('translations'),
            ])
            ->withCount('products')
            ->orderBy('name')
            ->get();
    }
    
    #[Computed]
    public function newArrivals()
    {
        if (!$this->keys->contains('new_arrivals')) return collect();
    
        return app(ProductService::class)->getFiltered([
            'sort'  => 'latest',
            'limit' => $this->theme->homepageSection('new_arrivals')['limit'] ?? 8,
        ]);
    }
    
    #[Computed]
    public function topRated()
    {
        if (!$this->keys->contains('top_rated')) return collect();
    
        return app(ProductService::class)->getFiltered([
            'sort'  => 'top_rated',
            'limit' => $this->theme->homepageSection('top_rated')['limit'] ?? 4,
        ]);
    }

    public function render()
    {
        return view('pages.index');
    }

};
?>

<x-slot name="header">
    <div class="grow hidden lg:block" id="header-search-portal"></div>
</x-slot>

<div class="flex flex-col gap-16 pb-16">

    <template x-teleport="#header-search-portal">
        <form action="{{ route('shop.products') }}" method="GET">
            <div class="relative">
                <span class="absolute top-1/2 -translate-y-1/2 sm:inset-s-header-search-px inset-s-m-header-search-px z-10 pointer-events-none">
                    @icon('search', 'w-5 h-5 sm:text-on-header/50 text-on-m-header/50')
                </span>
                <input
                    type="text"
                    name="search"
                    class="input header-input w-full sm:pr-[calc(var(--spacing-header-search-px)+2rem)]! pr-[calc(var(--spacing-m-header-search-px)+2rem)]! rounded-input-full!"
                    placeholder="{{ __('What are you looking for?') }}">
            </div>
        </form>
    </template>

    @foreach($this->sections as $section)
        @switch($section['key'])
            @case('hero')
                @include('pages.home._hero', ['section' => $section])
                @break
            @case('categories')
                @include('pages.home._categories', ['section' => $section])
                @break
            @case('new_arrivals')
                @include('pages.home._new-arrivals', ['section' => $section])
                @break
            @case('top_rated')
                @include('pages.home._top-rated', ['section' => $section])
                @break
            @case('promo_banner')
                @include('pages.home._promo-banner', ['section' => $section])
                @break
        @endswitch
    @endforeach

</div>