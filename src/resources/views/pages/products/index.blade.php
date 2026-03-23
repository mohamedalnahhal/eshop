<?php

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

new class extends Component
{
    use WithPagination;

    public function render()
    {
        $products = Product::paginate(12); 
        $categories = Category::all();

        return view('pages.products.index', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
};
?>

<x-slot name="header">
    <div class="grow hidden lg:block">
        <form action="{{ route('shop.products') }}" method="GET">
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
            @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
            <div class="relative">
                <span class="absolute inset-s-3 top-3 opacity-50">🔍</span>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full px-3 pr-10 py-3 bg-white border border-gray-300 shadow-sm shadow-gray-400/5 rounded-full focus:ring-4 focus:ring-blue-50 outline-none transition-all" 
                       placeholder="عن ماذا تبحث ؟">
            </div>
        </form>
    </div>
</x-slot>

<x-slot name="top">
    <x-breadcrumbs :links="[
            'المنتجات' => null,
    ]" />

    <h1 class="container text-2xl font-bold text-gray-600 mb-2">تصفح جميع منتجاتنا</h1>

    <div x-data="{ filtersOpen: false }" 
        @toggle-filters.window="filtersOpen = !filtersOpen"
        :class="filtersOpen ? 'bg-white' : 'bg-white/50 backdrop-blur-md'"
        class="py-4 sticky top-0 z-50 w-full lg:hidden">
        <form action="{{ route('shop.products') }}" method="GET" class="container max-lg:flex max-lg:flex-row max-lg:gap-4">
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
            @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
            <div class="relative max-lg:grow">
                <span class="absolute inset-s-3 top-3">🔍</span>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full px-3 pr-10 py-3 bg-white/50 backdrop-blur-md border border-gray-100 rounded-xl focus:ring-4 focus:ring-blue-50 outline-none transition-all shadow-sm" 
                       placeholder="عن ماذا تبحث ؟">
            </div>
            <button type="button" 
                    @click="$dispatch('toggle-filters')" 
                    class="lg:hidden flex items-center justify-center px-4 bg-white/50 backdrop-blur-md border border-gray-100 rounded-xl text-gray-600 focus:ring-4 focus:ring-blue-50 transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
            </button>
        </form>
    </div>
</x-slot>

<div>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start mt-4">
        <!-- filters -->
        <div x-data="{ showFilters: false }" 
             @toggle-filters.window="showFilters = !showFilters"
             class="lg:col-span-1 sticky top-20 h-max z-10 transition-all duration-300 bg-white max-lg:pb-6 max-lg:shadow-md max-lg:-mx-4 max-lg:px-4"
             :class="showFilters ? 'block' : 'hidden lg:block'">
            <form action="{{ route('shop.products') }}" method="GET" class="flex flex-col gap-8">
                @if(request('search')) 
                    <input type="hidden" name="search" value="{{ request('search') }}"> 
                @endif
                <div>
                    <label class="block text-xs font-black text-blue-900/40 uppercase mr-1 mb-2">الاقسام</label>
                    <select name="category" class="w-full px-3 py-2 bg-white border border-gray-100 rounded-lg focus:ring-4 focus:ring-blue-50 outline-none cursor-pointer shadow-sm appearance-none text-gray-600">
                        <option value="">كل المنتجات</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black text-blue-900/40 uppercase mr-1 mb-2">السعر (ريال)</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="من" 
                               class="w-full px-3 py-2 bg-white border border-gray-100 rounded-lg outline-none focus:ring-4 focus:ring-blue-50 shadow-sm">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="الى" 
                               class="w-full px-3 py-2 bg-white border border-gray-100 rounded-lg outline-none focus:ring-4 focus:ring-blue-50 shadow-sm">
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="grow bg-blue-600 text-white py-2 rounded-lg font-bold hover:bg-blue-700 transition shadow-xl shadow-blue-100 active:scale-95">
                       تحديث
                    </button>
                </div>
            </form>
        </div>
        <div class="lg:col-span-3 flex flex-col">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <livewire:product :product="$product"/>
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