<?php

use Livewire\Component;
use App\Services\CartService;
use Livewire\Attributes\Computed;

new class extends Component
{
    protected CartService $cartService;

    public function boot(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    #[Computed]
    public function cart()
    {
        return $this->cartService->getCart();
    }

    #[Computed]
    public function count()
    {
        return $this->cartService->getCount();
    }

    public function removeItem($itemId)
    {
        $this->cartService->remove($itemId);
    }
};
?>

<div>
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-3xl font-extrabold text-gray-900">سلة المشتريات 🛒</h1>
        <a href="{{ route('shop.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-2 bg-blue-50 px-4 py-2 rounded-lg transition">
            <span>&rarr;</span> المتجر
        </a>
    </div>

    @if($this->count > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-4">
                @php $totalPrice = 0; @endphp
                
                @foreach($this->cart->items as $item)
                    @php 
                        $itemSubtotal = $item->price * $item->quantity;
                        $totalPrice += $itemSubtotal;
                    @endphp
                    
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row items-center gap-4 hover:shadow-md transition">
                        <div class="flex flex-row gap-4">
                            <a href="{{ route('shop.product.show', ['id' => $item->product->id]) }}" class="block relative overflow-hidden w-24 h-24 bg-gray-100 rounded-xl items-center justify-center">
                                @php
                                    $ImagePath = $item->product->media->first()?->file_path;
                                @endphp
                                @if($ImagePath)
                                    <img src="{{ asset('storage/' . $ImagePath) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                @else
                                    <div class="w-full h-56 bg-gray-200 flex items-center justify-center text-gray-400 font-bold">
                                        No Image
                                    </div>
                                @endif
                            </a>

                            <div class="grow w-full">
                                <h3>
                                    <a href="{{ route('shop.product.show', ['id' => $item->product->id]) }}" class="block text-xl font-bold text-gray-800 hover:underline">{{ $item->product->name }}</a>
                                </h3>
                                <p class="text-gray-500 text-sm mb-2 overflow-hidden">
                                    {{ $item->product->description ? \Illuminate\Support\Str::limit($item->product->description, 60) : 'لا يوجد وصف متاح لهذا المنتج حالياً.' }}
                                </p>
                                <p class="text-gray-500 text-sm font-semibold">سعر الوحدة: ${{ number_format($item->price, 2) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto mt-4 sm:mt-0 border-t sm:border-t-0 pt-4 sm:pt-0 border-gray-100">
                            
                            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm font-bold text-gray-700">
                                الكمية: {{ $item->quantity }}
                            </div>
                            
                            <div class="text-xl font-black text-green-600 w-24 text-center">
                                ${{ number_format($itemSubtotal, 2) }}
                            </div>
                        
                            <button wire:click="removeItem({{ $item->id }})" type="button" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="حذف المنتج من السلة">
                                🗑️
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 h-fit sticky top-10">
                <h2 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">ملخص الطلب</h2>
                
                <div class="space-y-4 mb-6 text-gray-600 text-sm">
                    <div class="flex justify-between">
                        <span>المجموع الفرعي:</span>
                        <span class="font-bold text-gray-900">${{ number_format($totalPrice, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>رسوم الشحن:</span>
                        <span class="font-bold text-green-500">مجاني</span>
                    </div>
                </div>
                
                <div class="border-t pt-4 mb-8 border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">الإجمالي الكلي:</span>
                        <span class="text-3xl font-black text-green-600">${{ number_format($totalPrice, 2) }}</span>
                    </div>
                </div>

                <x-primary-button>
                    <span>إتمام الطلب للدفع</span> 💳
                </x-primary-button>
            </div>

        </div>
    @else
        <div class="p-16 text-center max-w-2xl mx-auto mt-10">
            <div class="text-7xl mb-6 opacity-80">🛒</div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">سلتك فارغة تماماً!</h2>
            <p class="text-gray-500 mb-8 text-lg">يبدو أنك لم تقم بإضافة أي منتجات رائعة إلى سلتك حتى الآن.</p>
            <a href="{{ route('shop.products') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-xl hover:bg-blue-700 transition shadow-md">
                تصفح المنتجات الآن
            </a>
        </div>
    @endif
</div>