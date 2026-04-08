<?php

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use App\Services\ProductService;
use Livewire\Attributes\Computed;

new class extends Component
{
    #[Computed]
    public function featuredCategories()
    {
        return Category::whereNull('parent_id')
            ->with(['children' => fn($q) => $q->withCount('products')])
            ->withCount('products')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function newArrivals()
    {
        return app(ProductService::class)->getFiltered([
            'sort' => 'latest',
            'limit' => 8,
        ]);
    }

    #[Computed]
    public function topRated()
    {
        return app(ProductService::class)->getFiltered([
            'sort' => 'top_rated',
            'limit' => 4,
        ]);
    }

    #[Computed]
    public function stats()
    {
        return [
            'products' => Product::count(),
            'categories' => Category::count(),
        ];
    }

    public function render()
    {
        return view('pages.index', [
            'featuredCategories' => $this->featuredCategories,
            'newArrivals' => $this->newArrivals,
            'topRated' => $this->topRated,
            'stats' => $this->stats,
        ]);
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
                    placeholder="عن ماذا تبحث ؟">
            </div>
        </form>
    </template>

    <section class="relative overflow-hidden rounded-card shadow-glow px-8 py-14 md:px-16 md:py-20">
        <div class="pointer-events-none absolute -top-20 -inset-e-20 w-72 h-72 rounded-theme-full bg-primary/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-16 -inset-s-16 w-56 h-56 rounded-theme-full bg-primary/10 blur-3xl"></div>

        <div class="relative flex flex-col md:flex-row items-center gap-10">
            <div class="flex-1">
                <h1 class="text-theme-4xl md:text-theme-5xl font-black text-theme leading-tight mb-4">
                    نأتيك بصافي اللبن من خير المزارع
                    <br class="max-lg:hidden">
                    <span class="text-primary">في مكان واحد</span>
                </h1>
                <p class="text-muted text-theme-lg sm:text-theme-xl mb-8 max-w-md me-auto">
                    تسوّق من أوسع تشكيلة من  شتى انواع الالبان والاجبان المختارة بعناية بأفضل الأسعار.
                </p>
                <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                    <a href="{{ route('shop.products') }}"
                       wire:navigate
                       class="btn btn-primary hover:opacity-75 font-bold rounded-cta! transition-all! shadow-glow! hover:-translate-y-0.5">
                        تصفح المنتجات
                        @icon('arrow-r', 'w-4 h-4 rotate-180')
                    </a>
                    <a href="#categories"
                       class="btn bg-surface-200 hover:bg-surface-300 text-theme font-bold rounded-cta! transition-all! hover:-translate-y-0.5">
                        الأقسام
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="categories">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-theme-2xl font-bold text-theme">تصفح الأقسام</h2>
            <a href="{{ route('shop.products') }}" wire:navigate
               class="text-theme-sm font-semibold text-primary hover:opacity-75 transition-colors flex items-center gap-1">
                عرض الكل
                @icon('chevron-r', 'w-4 h-4 rotate-180')
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($featuredCategories as $category)
                <div class="card h-fit overflow-hidden">
                    <a href="{{ route('shop.products', ['category' => $category->id]) }}"
                       class="flex items-center gap-3 px-5 py-4 hover:bg-surface-100 transition-colors group">
                        <div class="w-10 h-10 rounded-icon bg-primary/10 text-primary group-hover:bg-primary/15 flex items-center justify-center text-theme-xl shrink-0 transition-colors">
                            @icon('tag', 'w-5 h-5')
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="font-bold text-theme group-hover:text-primary! transition-colors">
                                {{ $category->name }}
                            </span>
                            <span class="block text-theme-xs text-muted">{{ $category->products_count }} منتج</span>
                        </div>
                        @icon('chevron-r', 'w-4 h-4 text-muted group-hover:text-primary! rotate-180 shrink-0 transition-colors')
                    </a>

                    @if($category->children->isNotEmpty())
                        <div class="border-t border-border-muted px-5 py-3 flex flex-wrap gap-2">
                            @foreach($category->children as $child)
                                <a href="{{ route('shop.products', ['category' => $child->id]) }}"
                                   class="badge bg-bg text-muted border border-border hover:bg-primary/10 hover:text-primary! hover:border-primary/30 transition-allall">
                                    {{ $child->name }}
                                    <span class="opacity-60">{{ $child->products_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <p class="col-span-full text-center text-muted py-8">لا توجد أقسام بعد.</p>
            @endforelse
        </div>
    </section>

    <section>
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <h2 class="text-theme-2xl font-bold text-theme">وصل حديثاً</h2>
                <span class="badge bg-primary text-on-primary">جديد</span>
            </div>
            <a href="{{ route('shop.products', ['sort' => 'latest']) }}" wire:navigate
               class="text-theme-sm font-semibold text-primary hover:opacity-75 transition-colors flex items-center gap-1">
                عرض الكل
                @icon('chevron-r', 'w-4 h-4 rotate-180')
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($newArrivals as $product)
                <livewire:listing-product :product="$product" :key="'new-'.$product->id" />
            @empty
                <div class="col-span-full text-center py-16 card border-2 border-dashed border-border">
                    <p class="text-theme-xl font-bold text-muted">لا توجد منتجات بعد.</p>
                </div>
            @endforelse
        </div>
    </section>

    <section>
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <h2 class="text-theme-2xl font-bold text-theme">الأعلى تقييماً</h2>
                <span class="badge bg-gold-surface text-on-gold border border-gold">★ مميز</span>
            </div>
            <a href="{{ route('shop.products', ['sort' => 'top_rated']) }}" wire:navigate
               class="text-theme-sm font-semibold text-primary hover:opacity-75 transition-opacity flex items-center gap-1">
                عرض الكل
                @icon('chevron-r', 'w-4 h-4 rotate-180')
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($topRated as $product)
                <livewire:listing-product :product="$product" :key="'top-'.$product->id" />
            @empty
                <div class="col-span-full text-center py-16 card border-2 border-dashed border-border">
                    <p class="text-theme-xl font-bold text-muted">لا توجد منتجات بعد.</p>
                </div>
            @endforelse
        </div>
    </section>

</div>