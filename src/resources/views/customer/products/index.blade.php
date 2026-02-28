
<div class="container mx-auto py-10">
    <header class="mb-10 text-center">
        <h1 class="text-4xl font-extrabold text-gray-900">متجر {{ $tenant->name }}</h1>
        <p class="text-lg text-gray-600">تصفح جميع المنتجات المتاحة لدينا</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @forelse($products as $product)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transition hover:scale-105">
                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-56 object-cover">
                <div class="p-5">
                    <h2 class="text-xl font-bold mb-2">{{ $product->name }}</h2>
                    <p class="text-gray-500 text-sm mb-4">{{ Str::limit($product->description, 60) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-green-600">${{ $product->price }}</span>
                        <a href="{{ route('shop.product.show', $product->slug) }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            عرض
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20 text-gray-500">
                لا توجد منتجات متوفرة حالياً في هذا المتجر.
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $products->links() }} {{-- روابط الترقيم --}}
    </div>
</div>
