<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سلة المشتريات - {{ $tenant->name ?? 'متجرنا' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Tajawal', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="container mx-auto py-10 px-4 max-w-6xl">
        
        {{-- رسائل التنبيه والنجاح --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 shadow-sm">
                <strong class="font-bold">ِAmazing!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 shadow-sm">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- رأس الصفحة وزر العودة --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-gray-900"> Shopping cart 🛒</h1>
            <a href="{{ route('shop.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-2 bg-blue-50 px-4 py-2 rounded-lg transition">
                <span>&rarr;</span> Continue shopping
            </a>
        </div>

        {{-- التحقق هل السلة موجودة وتحتوي على منتجات --}}
        @if($cart && $cart->items->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- قائمة المنتجات في السلة --}}
                <div class="lg:col-span-2 space-y-4">
                    @php $totalPrice = 0; @endphp
                    
                    @foreach($cart->items as $item)
                        @php 
                            // حساب السعر الفرعي للمنتج الواحد (السعر × الكمية)
                            $itemSubtotal = $item->product->price * $item->quantity;
                            // إضافته للمجموع الكلي
                            $totalPrice += $itemSubtotal;
                        @endphp
                        
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row items-center gap-4 hover:shadow-md transition">
                            
                            {{-- صورة المنتج --}}
                            <div class="w-24 h-24 flex-shrink-0 bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-2xl text-gray-400">📷</span>
                                @endif
                            </div>

                            {{-- تفاصيل المنتج --}}
                            <div class="flex-grow text-center sm:text-right w-full sm:w-auto">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $item->product->name }}</h3>
                                <p class="text-gray-500 text-sm font-semibold"> Price per one : ${{ number_format($item->product->price, 2) }}</p>
                            </div>

                            {{-- الكمية والمجموع وزر الحذف --}}
                            <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto mt-4 sm:mt-0 border-t sm:border-t-0 pt-4 sm:pt-0 border-gray-100">
                                
                                <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm font-bold text-gray-700">
                                    الكمية: {{ $item->quantity }}
                                </div>
                                
                                <div class="text-xl font-black text-green-600 w-24 text-center">
                                    ${{ number_format($itemSubtotal, 2) }}
                                </div>

                                {{-- زر الحذف (جهزته لك كـ Form لكي نبرمجه في الخطوة القادمة) --}}
                                <form action="#" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title=" Delete the product from the cart ">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ملخص الطلب والإجمالي --}}
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 h-fit sticky top-10">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4"> Order summary </h2>
                    
                    <div class="space-y-4 mb-6 text-gray-600 text-sm">
                        <div class="flex justify-between">
                            <span> Subtotal :</span>
                            <span class="font-bold text-gray-900">${{ number_format($totalPrice, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping fees :</span>
                            <span class="font-bold text-green-500">Free</span>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4 mb-8 border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900"> Total Price:</span>
                            <span class="text-3xl font-black text-green-600">${{ number_format($totalPrice, 2) }}</span>
                        </div>
                    </div>

                    <button class="w-full bg-blue-600 text-white text-lg font-bold py-3 px-4 rounded-xl hover:bg-blue-700 transition shadow-lg hover:shadow-xl flex justify-center items-center gap-2">
                        <span> Complete the payment</span> 💳
                    </button>
                </div>

            </div>
        @else
            {{-- حالة السلة الفارغة --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-16 text-center max-w-2xl mx-auto mt-10">
                <div class="text-7xl mb-6 opacity-80">🛒</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">سلتك فارغة تماماً!</h2>
                <p class="text-gray-500 mb-8 text-lg">يبدو أنك لم تقم بإضافة أي منتجات رائعة إلى سلتك حتى الآن.</p>
                <a href="{{ route('shop.index') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-xl hover:bg-blue-700 transition shadow-md">
                    تصفح المنتجات الآن
                </a>
            </div>
        @endif

    </div>

</body>
</html>