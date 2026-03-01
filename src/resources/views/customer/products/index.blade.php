<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر {{ $tenant->name ?? 'Alban Store' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Tajawal', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="container mx-auto py-10 px-4 max-w-7xl">
    
    {{-- الهيدر مع زر السلة --}}
    <header class="mb-10 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="text-center md:text-right">
            <h1 class="text-4xl font-black text-gray-900">متجر {{ $tenant->name ?? 'Alban Store' }}</h1>
            <p class="text-lg text-gray-600 mt-2">تصفح أفضل المنتجات المختارة لك</p>
        </div>
        
        <a href="{{ route('shop.cart.index') }}" class="flex items-center gap-3 bg-white border-2 border-blue-600 text-blue-600 px-6 py-3 rounded-2xl font-bold hover:bg-blue-600 hover:text-white transition-all shadow-sm">
            <span>سلة المشتريات</span>
            <span class="text-xl">🛒</span>
        </a>
    </header>

    {{-- قسم الفلترة المطور --}}
    <div class="bg-white p-6 rounded-3xl shadow-sm mb-10 border border-gray-100">
        <form action="{{ route('shop.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-end">
            
            {{-- البحث بالاسم --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 mr-1">ابحث عن منتج</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition" 
                           placeholder="اكتب اسم المنتج...">
                    <span class="absolute left-3 top-3 opacity-30">🔍</span>
                </div>
            </div>

            {{-- التصنيفات --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 mr-1">التصنيف</label>
                <select name="category" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none appearance-none cursor-pointer">
                    <option value="">جميع الأقسام</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        @isset($cat->products_count) ({{ $cat->products_count }}) @endisset
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- نطاق السعر --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 mr-1">السعر (من - إلى)</label>
                <div class="flex items-center gap-2">
                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" 
                           class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500">
                    <span class="text-gray-400">-</span>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="999" 
                           class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- أزرار التحكم --}}
            <div class="flex gap-2">
                <button type="submit" class="flex-grow bg-blue-600 text-white py-3 rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100">
                    تطبيق الفلترة
                </button>
                <a href="{{ route('shop.index') }}" class="px-5 py-3 bg-gray-100 text-gray-500 rounded-2xl hover:bg-gray-200 transition text-center" title="إعادة ضبط">
                    🔄
                </a>
            </div>
        </form>
    </div>

    {{-- شبكة المنتجات --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($products as $product)
            <div class="group bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                
                {{-- صورة المنتج --}}
                <div class="relative overflow-hidden aspect-square">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                    @else
                        <div class="w-full h-full bg-gray-100 flex flex-col items-center justify-center text-gray-400">
                            <span class="text-4xl mb-2">📦</span>
                            <span class="text-xs font-bold uppercase tracking-widest">No Image</span>
                        </div>
                    @endif
                    
                    @if($product->category)
                        <span class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-blue-600 shadow-sm">
                            {{ $product->category->name }}
                        </span>
                    @endif
                </div>

                {{-- تفاصيل المنتج --}}
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-2 text-gray-800 group-hover:text-blue-600 transition">{{ $product->name }}</h2>
                    <p class="text-gray-500 text-sm mb-6 line-clamp-2 min-h-[2.5rem]">
                        {{ $product->description }}
                    </p>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-400 font-bold">السعر</span>
                            <span class="text-2xl font-black text-green-600">${{ number_format($product->price, 2) }}</span>
                        </div>
                        
                        <a href="{{ route('shop.product.show', ['id' => $product->id]) }}" 
                           class="bg-gray-900 text-white px-5 py-2.5 rounded-xl hover:bg-blue-600 transition shadow-md font-bold text-sm">
                           تفاصيل المنتج
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                <div class="text-6xl mb-4">🔎</div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">لم نجد أي نتائج!</h3>
                <p class="text-gray-500">جرب تغيير كلمات البحث أو الفلاتر التي اخترتها.</p>
                <a href="{{ route('shop.index') }}" class="inline-block mt-6 text-blue-600 font-bold underline">عرض كل المنتجات</a>
            </div>
        @endforelse
    </div>

    {{-- الترقيم --}}
    <div class="mt-16 flex justify-center">
        <div class="bg-white px-4 py-2 rounded-2xl shadow-sm border border-gray-100">
            {{ $products->links() }}
        </div>
    </div>
</div>

</body>
</html>