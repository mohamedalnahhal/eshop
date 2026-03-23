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

<div>
    <div class="bg-white/50 backdrop-blur-md p-8 rounded-[2.5rem] shadow-sm mb-12 border border-white">
        <form action="{{ route('shop.products') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 items-end">
            <div>
                <label class="block text-xs font-black text-blue-900/40 uppercase mr-1 mb-2">ابحث عن منتج</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full pl-4 pr-10 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 outline-none transition-all shadow-sm" 
                           placeholder="What do you crave today?">
                    <span class="absolute left-4 top-4 opacity-20">🔍</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-blue-900/40 uppercase mr-1 mb-2">الاقسام</label>
                <select name="category" class="w-full px-4 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 outline-none cursor-pointer shadow-sm appearance-none font-bold text-gray-600">
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
                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="From" 
                           class="w-full px-3 py-4 bg-white border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-50 shadow-sm text-center">
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="To" 
                           class="w-full px-3 py-4 bg-white border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-50 shadow-sm text-center">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-grow bg-blue-600 text-white py-4 rounded-2xl font-bold hover:bg-blue-700 transition shadow-xl shadow-blue-100 active:scale-95">
                   تحديث
                </button>
                <a href="{{ route('shop.index') }}" class="px-5 py-4 bg-white border border-gray-100 text-gray-400 rounded-2xl hover:bg-gray-50 transition shadow-sm flex items-center justify-center">
                   🔄
                </a>
            </div>
        </form>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
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