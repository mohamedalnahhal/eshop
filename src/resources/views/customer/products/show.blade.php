<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - {{ $tenant->name ?? 'متجرنا' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Tajawal', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="container mx-auto py-10 px-4 max-w-6xl">
        
        {{-- رسالة النجاح عند إضافة تقييم --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 text-center shadow-sm" role="alert">
                <strong class="font-bold">نجاح!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- زر العودة للمتجر --}}
        <div class="mb-8">
            <a href="{{ route('shop.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-2">
                <span>&rarr;</span> العودة إلى المتجر
            </a>
        </div>

        {{-- بطاقة تفاصيل المنتج --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- قسم الصورة --}}
                <div class="bg-gray-100 flex items-center justify-center p-8">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="max-w-full h-auto rounded-lg shadow-sm object-cover">
                    @else
                        <div class="w-full h-96 flex flex-col items-center justify-center text-gray-400">
                            <span class="text-6xl mb-4">📷</span>
                            <p>لا توجد صورة لهذا المنتج</p>
                        </div>
                    @endif
                </div>

                {{-- قسم تفاصيل المنتج --}}
                <div class="p-8 md:p-12 flex flex-col justify-center">
                    
                    {{-- عرض اسم القسم إذا كان موجوداً --}}
                    @if($product->category)
                        <span class="text-sm font-bold text-blue-500 tracking-wider mb-2">
                            {{ $product->category->name }}
                        </span>
                    @endif

                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">{{ $product->name }}</h1>
                    
                    <div class="text-3xl font-black text-green-600 mb-6">
                        ${{ number_format($product->price, 2) }}
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-bold mb-2 text-gray-800">وصف المنتج:</h3>
                        <p class="text-gray-600 leading-relaxed">
                            {{ $product->description ?? 'لا يوجد وصف متاح لهذا المنتج حالياً.' }}
                        </p>
                    </div>

                    {{-- أزرار التفاعل --}}
                    <div class="flex gap-4 mt-auto">
                        <button class="flex-1 bg-blue-600 text-white text-lg font-bold py-3 px-6 rounded-xl hover:bg-blue-700 transition shadow-lg hover:shadow-xl">
                            إضافة للسلة 🛒
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- قسم التقييمات والمراجعات --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            {{-- عمود عرض التقييمات السابقة --}}
            <div class="lg:col-span-2">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">آراء الزبائن ({{ $product->reviews ? $product->reviews->count() : 0 }})</h2>
                
                <div class="space-y-6">
                    @forelse($product->reviews ?? [] as $review)
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="font-bold text-lg">{{ $review->customer_name }}</h4>
                                <div class="text-yellow-400 text-lg">
                                    {{-- عرض النجوم بناءً على التقييم --}}
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-gray-600 italic">"{{ $review->comment ?? 'لم يترك تعليقاً.' }}"</p>
                            <span class="text-xs text-gray-400 mt-4 block">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <p class="text-gray-500">لا توجد تقييمات حتى الآن. كن أول من يقيّم هذا المنتج!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- عمود إضافة تقييم جديد --}}
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 h-fit">
                <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">أضف تقييمك</h3>
                
                {{-- نموذج إضافة التقييم --}}
                <form action="{{ route('shop.product.review.store', ['id' => $product->id]) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">الاسم <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="أدخل اسمك">
                        @error('customer_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">التقييم <span class="text-red-500">*</span></label>
                        <select name="rating" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="5">5 نجوم - ممتاز ⭐️⭐️⭐️⭐️⭐️</option>
                            <option value="4">4 نجوم - جيد جداً ⭐️⭐️⭐️⭐️</option>
                            <option value="3">3 نجوم - متوسط ⭐️⭐️⭐️</option>
                            <option value="2">نجمتان - مقبول ⭐️⭐️</option>
                            <option value="1">نجمة واحدة - سيء ⭐️</option>
                        </select>
                        @error('rating') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">رأيك (اختياري)</label>
                        <textarea name="comment" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="اكتب رأيك في المنتج هنا..."></textarea>
                        @error('comment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition shadow-md">
                        إرسال التقييم
                    </button>
                </form>
            </div>

        </div>
    </div>

</body>
</html>