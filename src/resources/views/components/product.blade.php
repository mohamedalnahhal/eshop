<?php

use Livewire\Component;
use App\Services\CartService;

new class extends Component
{
    public $product;
    
    public function mount($product)
    {
        $this->product = $product;
    }

    public function addToCart(CartService $cartService)
    {
        $cartService->add($this->product->id);

        $this->dispatch('cart-updated');

        session()->flash('success', 'تمت إضافة المنتج إلى السلة بنجاح! 🛒');
    }
};
?>

<div class="group bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.03)] overflow-hidden border border-gray-50 transition-all duration-500 hover:shadow-[0_20px_60px_rgba(59,130,246,0.12)] hover:-translate-y-2">
    <a href="{{ route('shop.product.show', ['id' => $product->id]) }}" class="block relative overflow-hidden bg-[#f8fafc]">
        <div class="aspect-square">
            @php
                $ImagePath = $product->media->first()?->file_path;
            @endphp
            @if($ImagePath)
                <img src="{{ asset('storage/' . $ImagePath) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
            @else
                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400 font-bold">
                    No Image
                </div>
            @endif
        </div>
        <!-- first category -->
        <div class="absolute top-5 right-5 flex flex-col gap-2">
            @if($product->categories->isNotEmpty())
                <span class="bg-white/90 backdrop-blur-md px-4 py-1.5 rounded-2xl text-[10px] font-black text-blue-600 shadow-sm border border-white/50 uppercase">
                    {{ $product->categories->first()->name }}
                </span>
            @endif
        </div>
    </a>
    <div class="p-5 flex flex-col grow">
        <h2>
            <a href="{{ route('shop.product.show', ['id' => $product->id]) }}" class="block text-xl font-bold mb-2 text-gray-800 hover:underline">{{ $product->name }}</a>
        </h2>
        <p class="text-gray-500 text-sm mb-4 overflow-hidden">
            {{ $product->description ? \Illuminate\Support\Str::limit($product->description, 60) : 'لا يوجد وصف متاح لهذا المنتج حالياً.' }}
        </p>
        
        <div class="mt-auto flex flex-col gap-3">
            <div class="flex flex-row items-center gap-4">
                <span class="text-2xl font-bold text-green-600">
                    ${{ number_format($product->price, 2) }}
                </span>
                @if($product->stock > 0)
                    <div class="w-fit bg-green-100 text-green-700 px-2 py-0.5 rounded-md text-sm font-bold">
                        متوفر ({{ $product->stock }})
                    </div>
                @else
                    <div class="w-fit bg-orange-100 text-orange-600 px-2 py-0.5 rounded-md text-sm font-bold">
                        نفذ
                    </div>
                @endif
            </div>
            <div class="border-b border-gray-200 pb-4 mb-1">
                <x-rating-stars :rating="$product->avg_rating" :reviewsCount="$product->reviews_count" />
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('shop.product.show', ['id' => $product->id]) }}" 
                class="flex-1 text-center bg-gray-100 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-200 transition shadow-sm font-bold text-sm" wire:navigate>
                    عرض
                </a>

                <button wire:click="addToCart" class="w-full bg-blue-600 text-white text-sm font-bold px-2 py-2 cursor-pointer rounded-lg hover:bg-blue-700 transition shadow-lg hover:shadow-xl flex justify-center items-center gap-2">
                    السلة 🛒
                </button>
            </div>
        </div>
    </div>
</div>