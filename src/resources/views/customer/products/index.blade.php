<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر {{ $tenant->name ?? 'Alban Store' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; }
    </style>
</head>
<body class="bg-[#fcfdfe] text-gray-800">

<div class="container mx-auto py-10 px-4 max-w-7xl">
    
    {{-- الهيدر --}}
    <header class="mb-12 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="text-center md:text-right">
            <h1 class="text-5xl font-black text-gray-900 tracking-tight">
                <span class="text-blue-600 italic">ALBAN</span> STORE
            </h1>
        </div>
        
        <a href="{{ route('shop.cart.index') }}" class="group flex items-center gap-4 bg-white border border-gray-100 p-2 pr-6 rounded-2xl shadow-sm hover:shadow-md transition-all">
            <div class="flex flex-col text-left">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Your Cart</span>
                <span class="text-sm font-black text-gray-900">0.00 U.S</span>
            </div>
            <div class="bg-blue-600 text-white w-12 h-12 rounded-xl flex items-center justify-center shadow-lg shadow-blue-100 group-hover:scale-110 transition-transform">
                <span class="text-xl">🛒</span>
            </div>
        </a>
    </header>

    {{-- قسم الفلترة --}}
    <div class="bg-white/50 backdrop-blur-md p-8 rounded-[2.5rem] shadow-sm mb-12 border border-white">
        <form action="{{ route('shop.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 items-end">
            <div>
                <label class="block text-xs font-black text-blue-900/40 uppercase mr-1 mb-2">Search for a product</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full pl-4 pr-10 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 outline-none transition-all shadow-sm" 
                           placeholder="What do you crave today?">
                    <span class="absolute left-4 top-4 opacity-20">🔍</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-blue-900/40 uppercase mr-1 mb-2">Sections</label>
                <select name="category" class="w-full px-4 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 outline-none cursor-pointer shadow-sm appearance-none font-bold text-gray-600">
                    <option value="">All products</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-black text-blue-900/40 uppercase mr-1 mb-2">Your budget (SAR)</label>
                <div class="flex items-center gap-2">
                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="From" 
                           class="w-full px-3 py-4 bg-white border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-50 shadow-sm text-center">
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="To" 
                           class="w-full px-3 py-4 bg-white border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-50 shadow-sm text-center">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-grow bg-blue-600 text-white py-4 rounded-2xl font-bold hover:bg-blue-700 transition shadow-xl shadow-blue-100 active:scale-95">
                   Update results
                </button>
                <a href="{{ route('shop.index') }}" class="px-5 py-4 bg-white border border-gray-100 text-gray-400 rounded-2xl hover:bg-gray-50 transition shadow-sm flex items-center justify-center">
                    🔄
                </a>
            </div>
        </form>
    </div>

    {{-- شبكة المنتجات --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
        @forelse($products as $product)
            <div class="group bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] overflow-hidden border border-gray-50 transition-all duration-500 hover:shadow-[0_20px_60px_rgba(59,130,246,0.12)] hover:-translate-y-2">
                
                {{-- رابط الصورة --}}
                <a href="{{ route('shop.product.show', ['id' => $product->id]) }}" class="block relative overflow-hidden aspect-[4/5] bg-[#f8fafc]">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                             class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-200">
                            <span class="text-6xl">🥛</span>
                        </div>
                    @endif
                    
                    <div class="absolute top-5 right-5 flex flex-col gap-2">
                        @if($product->category)
                            <span class="bg-white/90 backdrop-blur-md px-4 py-1.5 rounded-2xl text-[10px] font-black text-blue-600 shadow-sm border border-white/50 uppercase">
                                {{ $product->category->name }}
                            </span>
                        @endif
                    </div>
                </a>

                {{-- المحتوى السفلي --}}
                <div class="p-8">
                    {{-- اسم المنتج --}}
                    <a href="{{ route('shop.product.show', ['id' => $product->id]) }}" class="block mb-4">
                        <h2 class="text-xl font-bold mb-1 text-gray-800 group-hover:text-blue-600 transition-colors">{{ $product->name }}</h2>
                        
                   <p class="text-gray-400 text-sm font-medium line-clamp-2 leading-relaxed min-h-[40px]">
                        {{ $product->description ?? 'No description is currently available.' }}
        </p>
        </p>
    </a>
                    </a>

                    <div class="flex flex-col gap-6 pt-6 border-t border-gray-50">
                        <div class="flex justify-between items-center">
                            {{-- السعر --}}
                            <div class="flex flex-col">
                                <span class="text-[10px] text-gray-400 font-bold uppercase mb-1">Price</span>
                                <span class="text-2xl font-black text-blue-600">
                                    {{ number_format($product->price, 2) }}
                                    <span class="text-xs text-blue-300 font-bold mr-1">U.S</span>
                                </span>
                            </div>

                            {{-- زر السلة القابل للضغط --}}
                            <form action="{{ route('shop.cart.add', ['id' => $product->id]) }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="bg-blue-50 text-blue-600 w-12 h-12 flex items-center justify-center rounded-2xl hover:bg-blue-600 hover:text-white transition-all shadow-sm active:scale-90">
                                    <span class="text-xl">🛒</span>
                                </button>
                            </form>
                        </div>

                        {{-- زر التفاصيل --}}
                        <a href="{{ route('shop.product.show', ['id' => $product->id]) }}" 
                           class="w-full bg-gray-900 text-white text-center py-4 rounded-2xl font-bold hover:bg-blue-600 transition-all shadow-lg active:scale-[0.98]">
                           View product details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-32 bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                <div class="text-8xl mb-6">🔍</div>
                <h3 class="text-3xl font-black text-gray-800">We didn't find any products!</h3>
                <a href="{{ route('shop.index') }}" class="inline-block mt-8 bg-blue-600 text-white px-10 py-4 rounded-2xl font-bold">List All</a>
            </div>
        @endforelse
    </div>
</div>

</body>
</html>