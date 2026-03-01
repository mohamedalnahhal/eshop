<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر {{ $tenant->name ?? 'Alban Store' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Tajawal', sans-serif; }</style>
</head>
<body class="bg-gray-50">

<div class="container mx-auto py-10 px-4">
    
    {{-- إضافة رسائل النجاح أو الخطأ هنا لتظهر للمشتري عند الإضافة للسلة --}}
    @if(session('success'))
        <div class="max-w-4xl mx-auto bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 text-center shadow-sm">
            <strong class="font-bold">رائع!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-4xl mx-auto bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 text-center shadow-sm">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <header class="mb-10 text-center">
        <h1 class="text-4xl font-extrabold text-gray-900">متجر {{ $tenant->name ?? 'Alban Store' }}</h1>
        <p class="text-lg text-gray-600 mt-2">تصفح جميع منتجاتنا</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @forelse($products as $product)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 transition hover:shadow-2xl flex flex-col">
                
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-56 object-cover">
                @else
                    <div class="w-full h-56 bg-gray-200 flex items-center justify-center text-gray-400 font-bold">
                        No Image
                    </div>
                @endif

                <div class="p-5 flex flex-col flex-grow">
                    <h2 class="text-xl font-bold mb-2 text-gray-800">{{ $product->name }}</h2>
                    <p class="text-gray-500 text-sm mb-4 h-12 overflow-hidden">
                        {{ \Illuminate\Support\Str::limit($product->description, 60) }}
                    </p>
                    
                    {{-- تم تعديل هذا القسم ليحتوي على السعر وزرين (عرض + إضافة للسلة) --}}
                    <div class="mt-auto flex flex-col gap-3">
                        <span class="text-2xl font-bold text-green-600 border-b border-gray-100 pb-2 mb-1 block text-center">
                            ${{ number_format($product->price, 2) }}
                        </span>
                        
                        <div class="flex gap-2">
                            {{-- زر العرض القديم --}}
                            <a href="{{ route('shop.product.show', ['id' => $product->id]) }}" 
                               class="flex-1 text-center bg-gray-100 text-gray-800 px-2 py-2 rounded-lg hover:bg-gray-200 transition shadow-sm font-bold text-sm">
                                عرض
                            </a>

                            {{-- زر الإضافة للسلة الجديد (مربوط بالكنترولر) --}}
                            <form action="{{ route('shop.cart.add', ['id' => $product->id]) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-blue-600 text-white px-2 py-2 rounded-lg hover:bg-blue-700 transition shadow-md font-bold text-sm flex justify-center items-center gap-1">
                                    <span>سلة</span> 🛒
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
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

</body>
</html>