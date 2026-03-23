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
        
        <div class="mt-auto flex flex-col gap-3">
            <span class="text-2xl font-bold text-green-600 border-b border-gray-100 pb-2 mb-1 block text-center">
                ${{ number_format($product->price, 2) }}
            </span>
            
            <div class="flex gap-2">
                <a href="{{ route('shop.product.show', ['id' => $product->id]) }}" 
                class="flex-1 text-center bg-gray-100 text-gray-800 px-2 py-2 rounded-lg hover:bg-gray-200 transition shadow-sm font-bold text-sm" wire:navigate>
                    عرض
                </a>

                <button wire:click="addToCart" class="w-full bg-blue-600 text-white text-sm font-bold px-2 py-2 cursor-pointer rounded-lg hover:bg-blue-700 transition shadow-lg hover:shadow-xl flex justify-center items-center gap-2">
                    السلة 🛒
                </button>
            </div>
        </div>
    </div>
</div>