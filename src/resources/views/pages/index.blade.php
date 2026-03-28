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
                <span class="absolute inset-s-3 top-3 opacity-50">🔍</span>
                <input 
                    type="text"
                name="search"
                    class="w-full px-3 pr-10 py-3 bg-white border border-gray-300 shadow-sm shadow-gray-400/5 rounded-full focus:ring-4 focus:ring-blue-50 outline-none transition-all" 
                    placeholder="عن ماذا تبحث ؟">
            </div>
        </form>
    </template>

    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-50 via-white to-indigo-50 border border-gray-100 shadow-sm shadow-gray-200/50 mt-6 px-8 py-14 md:px-16 md:py-20">
        <div class="pointer-events-none absolute -top-20 -end-20 w-72 h-72 rounded-full bg-blue-100/60 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-16 -start-16 w-56 h-56 rounded-full bg-indigo-100/50 blur-3xl"></div>

        <div class="relative flex flex-col md:flex-row items-center gap-10">
            <div class="flex-1 text-center md:text-start">
                <h1 class="text-4xl md:text-5xl font-black text-gray-800 leading-tight mb-4">
                    نأتيك بصافي اللبن من خير المزارع
                    <span class="text-blue-600">في مكان واحد</span>
                </h1>
                <p class="text-gray-500 text-lg mb-8 max-w-md me-auto">
                    تسوّق من أوسع تشكيلة من  شتى انواع الالبان والاجبان المختارة بعناية بأفضل الأسعار.
                </p>
                <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                    <a href="{{ route('shop.products') }}"
                       wire:navigate
                       class="inline-flex items-center gap-2 px-7 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-full transition-all shadow-md shadow-blue-300/40 hover:shadow-lg active:scale-95 hover:shadow-blue-300/50 hover:-translate-y-0.5">
                        تصفح المنتجات
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 rotate-180" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#categories"
                       class="inline-flex items-center gap-2 px-7 py-3 bg-white hover:bg-gray-50 text-gray-700 font-bold rounded-full border border-gray-200 transition-all shadow-sm active:scale-95 hover:-translate-y-0.5">
                        الأقسام
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="categories">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-700">تصفح الأقسام</h2>
            <a href="{{ route('shop.products') }}" wire:navigate
               class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors flex items-center gap-1">
                عرض الكل
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 rotate-180" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($featuredCategories as $category)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm shadow-gray-200/40 overflow-hidden">
                    <a href="{{ route('shop.products', ['category' => $category->id]) }}"
                       class="flex items-center gap-3 px-5 py-4 hover:bg-blue-50/50 transition-colors group">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center text-xl shrink-0 transition-colors">
                            {{ $category->icon ?? '📦' }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="font-bold text-gray-700 group-hover:text-blue-700 transition-colors">
                                {{ $category->name }}
                            </span>
                            <span class="block text-xs text-gray-400">{{ $category->products_count }} منتج</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-blue-400 rotate-180 shrink-0 transition-colors" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    @if($category->children->isNotEmpty())
                        <div class="border-t border-gray-100 px-5 py-3 flex flex-wrap gap-2">
                            @foreach($category->children as $child)
                                <a href="{{ route('products.index', ['category' => $child->id]) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-gray-50 hover:bg-blue-50 text-gray-500 hover:text-blue-600 border border-gray-100 hover:border-blue-200 transition-all">
                                    {{ $child->name }}
                                    <span class="opacity-60">{{ $child->products_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <p class="col-span-full text-center text-gray-400 py-8">لا توجد أقسام بعد.</p>
            @endforelse
        </div>
    </section>

    <section>
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-gray-700">وصل حديثاً</h2>
                <span class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-blue-600 text-white">جديد</span>
            </div>
            <a href="{{ route('shop.products', ['sort' => 'latest']) }}" wire:navigate
               class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors flex items-center gap-1">
                عرض الكل
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 rotate-180" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($newArrivals as $product)
                <livewire:listing-product :product="$product" :key="'new-'.$product->id" />
            @empty
                <div class="col-span-full text-center py-16 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                    <p class="text-xl font-bold text-gray-400">لا توجد منتجات بعد.</p>
                </div>
            @endforelse
        </div>
    </section>

    <section>
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-gray-700">الأعلى تقييماً</h2>
                <span class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-700 border border-amber-200">⭐ مميز</span>
            </div>
            <a href="{{ route('shop.products', ['sort' => 'top_rated']) }}" wire:navigate
               class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors flex items-center gap-1">
                عرض الكل
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 rotate-180" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($topRated as $product)
                <livewire:listing-product :product="$product" :key="'top-'.$product->id" />
            @empty
                <div class="col-span-full text-center py-16 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                    <p class="text-xl font-bold text-gray-400">لا توجد منتجات بعد.</p>
                </div>
            @endforelse
        </div>
    </section>

</div>