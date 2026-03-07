<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - {{ $tenant->name ?? 'متجرنا' }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Tajawal', sans-serif; scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#f8fafc] text-gray-800">

    <div class="container mx-auto py-10 px-4 max-w-7xl">
        
        {{-- التنبيهات --}}
        @if(session('success'))
            <div class="bg-green-500 text-white px-6 py-4 rounded-2xl mb-8 text-center shadow-lg animate-bounce">
                <span class="font-bold">✅ {{ session('success') }}</span>
            </div>
        @endif

        {{-- التنقل --}}
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('shop.index') }}" class="group flex items-center gap-2 text-gray-500 hover:text-blue-600 transition-all">
                <span class="bg-white p-2 rounded-full shadow-sm group-hover:bg-blue-50 transition-colors">&rarr;</span>
                <span class="font-medium text-lg">العودة للمتجر</span>
            </a>
        </div>

        {{-- القسم الرئيسي: تفاصيل المنتج الاحترافية --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start" 
             x-data="{ 
                activeImage: '{{ $product->media->first() ? asset('storage/' . $product->media->first()->file_path) : 'https://via.placeholder.com/600x600?text=No+Image' }}',
                activeTab: 'details'
             }">
            
            {{-- 1. معرض الصور (Amazon Style) --}}
            <div class="lg:col-span-7 bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col md:flex-row-reverse gap-6">
                
                {{-- الصورة الرئيسية الكبيرة --}}
                <div class="flex-1 bg-gray-50 rounded-3xl overflow-hidden relative group aspect-square flex items-center justify-center border border-gray-50">
                    <img :src="activeImage" 
                         alt="{{ $product->name }}" 
                         class="max-w-full max-h-[500px] object-contain transition duration-700 ease-in-out transform group-hover:scale-110">
                    
                    <div class="absolute top-4 right-4">
                        <span class="bg-white/90 backdrop-blur px-4 py-2 rounded-full text-xs font-bold shadow-sm border border-gray-100">تكبير 🔍</span>
                    </div>
                </div>

                {{-- القائمة الجانبية للصور المصغرة --}}
                @if($product->media->count() > 0)
                    <div class="flex md:flex-col gap-3 overflow-x-auto md:overflow-y-auto max-h-[500px] custom-scrollbar pb-2 pr-2">
                        @foreach($product->media as $media)
                            <button @click="activeImage = '{{ asset('storage/' . $media->file_path) }}'" 
                                    class="relative flex-shrink-0 w-20 h-20 rounded-2xl border-2 overflow-hidden transition-all duration-300"
                                    :class="activeImage === '{{ asset('storage/' . $media->file_path) }}' ? 'border-blue-600 ring-4 ring-blue-50' : 'border-transparent hover:border-blue-200'">
                                <img src="{{ asset('storage/' . $media->file_path) }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- 2. قسم المعلومات والطلب --}}
            <div class="lg:col-span-5 flex flex-col gap-6">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                    @if($product->category)
                        <span class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 text-xs font-black rounded-full mb-4 uppercase tracking-widest">
                            {{ $product->category->name }}
                        </span>
                    @endif

                    <h1 class="text-4xl font-black text-gray-900 mb-4 leading-tight">
                        {{ $product->name }}
                    </h1>

                    <div class="flex items-center gap-4 mb-8">
                        <div class="text-5xl font-black text-blue-600">
                            ${{ number_format($product->price, 2) }}
                        </div>
                        <div class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-sm font-bold">
                            متوفر 🟢
                        </div>
                    </div>

                    <div class="space-y-4 mb-8 text-gray-600 leading-relaxed text-lg">
                        <p>{{ Str::limit($product->description, 200) }}</p>
                    </div>

                    {{-- زر الإضافة للسلة --}}
                    <form action="{{ route('shop.cart.add', ['id' => $product->id]) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xl font-black py-5 rounded-2xl transition-all shadow-xl shadow-blue-200 flex justify-center items-center gap-4 group active:scale-95">
                            <span>إضافة إلى العربة</span>
                            <span class="text-2xl group-hover:rotate-12 transition-transform">🛒</span>
                        </button>
                    </form>
                </div>

                {{-- بطاقة حوافز الشراء --}}
                <div class="bg-blue-900 text-white p-6 rounded-[2rem] shadow-lg flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="text-3xl">🚚</div>
                        <div>
                            <p class="font-bold">شحن سريع ومجاني</p>
                            <p class="text-blue-200 text-xs">للطلبات فوق 50 دولار</p>
                        </div>
                    </div>
                    <div class="text-2xl opacity-20">★</div>
                </div>
            </div>
        </div>

        {{-- القسم السفلي: تبويبات (وصف كامل - تقييمات) --}}
        <div class="mt-16 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden" x-data="{ tab: 'reviews' }">
            <div class="flex border-b border-gray-100 bg-gray-50/50">
                <button @click="tab = 'details'" :class="tab === 'details' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-400'" class="px-8 py-5 font-bold border-b-4 transition-all">تفاصيل المنتج</button>
                <button @click="tab = 'reviews'" :class="tab === 'reviews' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-400'" class="px-8 py-5 font-bold border-b-4 transition-all">المراجعات ({{ $product->reviews->count() }})</button>
            </div>

            <div class="p-8">
                {{-- التبويب 1: الوصف الكامل --}}
                <div x-show="tab === 'details'" x-cloak class="prose max-w-none text-gray-600">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 italic">عن هذا المنتج</h3>
                    <p class="text-lg leading-loose">{{ $product->description }}</p>
                </div>

                {{-- التبويب 2: التقييمات --}}
                <div x-show="tab === 'reviews'" x-cloak>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                        {{-- قائمة المراجعات --}}
                        <div class="lg:col-span-2 space-y-6">
                            @forelse($product->reviews as $review)
                                <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-black">
                                                {{ Str::upper(Str::substr($review->customer_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800">{{ $review->customer_name }}</h4>
                                                <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="text-lg {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}">★</span>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-gray-600 italic leading-relaxed">"{{ $review->comment }}"</p>
                                </div>
                            @empty
                                <div class="text-center py-10">
                                    <p class="text-gray-400">لا توجد مراجعات حالياً. كن الملهم الأول!</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- فورم التقييم --}}
                        <div class="bg-gray-50 p-8 rounded-3xl border border-gray-100">
                            <h3 class="text-xl font-black mb-6">اترك انطباعك ✨</h3>
                            <form action="{{ route('shop.product.review.store', ['id' => $product->id]) }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="text" name="customer_name" required placeholder="اسمك الكريم" class="w-full px-4 py-3 rounded-xl border-none ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                                <select name="rating" required class="w-full px-4 py-3 rounded-xl border-none ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                                    <option value="5">⭐⭐⭐⭐⭐ ممتاز</option>
                                    <option value="4">⭐⭐⭐⭐ جيد جداً</option>
                                    <option value="3">⭐⭐⭐ متوسط</option>
                                    <option value="2">⭐⭐ مقبول</option>
                                    <option value="1">⭐ سيء</option>
                                </select>
                                <textarea name="comment" rows="4" placeholder="كيف كانت تجربتك؟" class="w-full px-4 py-3 rounded-xl border-none ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                                <button type="submit" class="w-full bg-gray-900 text-white font-bold py-4 rounded-xl hover:bg-black transition-all">إرسال الآن</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>