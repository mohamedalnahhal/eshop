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

        session()->flash('success', 'تمت إضافة المنتج إلى السلة بنجاح!');
    }
};
?>

<div class="group card overflow-hidden transition-all duration-500 hover:-translate-y-2">
    <a href="{{ route('shop.product.show', ['id' => $product->id]) }}" class="block relative overflow-hidden">
        <div class="aspect-square">
            @php
                $ImagePath = $product->media->first()?->file_path;
            @endphp
            @if($ImagePath)
                <img src="{{ asset('storage/' . $ImagePath) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
            @else
                <div class="w-full h-full bg-surface-200 flex items-center justify-center text-muted font-bold">
                    @icon('image', 'w-10 h-10')
                </div>
            @endif
        </div>
    </a>
    <div class="p-5 flex flex-col grow">
        <h2 class="line-clamp-1">
            <a href="{{ route('shop.product.show', ['id' => $product->id]) }}" class="block text-theme-xl font-bold mb-2 text-theme hover:underline">{{ $product->name }}</a>
        </h2>
        <div class="mb-4">
            <x-rating-stars :rating="$product->avg_rating" :reviewsCount="$product->reviews_count" />
        </div>
        
        @if($product->categories->isNotEmpty())
            <div class="@container flex flex-row gap-2 mb-4 items-center w-full overflow-hidden">
                @foreach ($product->categories->take(2) as $loopIndex => $cat)
                    <span class="badge bg-card-bg/90 text-primary shadow-card border border-border min-w-0 shrink flex items-center {{ $loopIndex === 1 ? 'hidden! @[15rem]:flex!' : '' }}">
                        <span class="truncate block w-full" dir="auto">
                            {{ $cat->name }}
                        </span>
                    </span>
                @endforeach
                
                @if ($product->categories->count() > 2)
                    <span class="badge bg-secondary text-on-secondary shadow-card border border-border-muted shrink-0">
                        اخرى
                    </span>
                @endif
            </div>
        @endif

        <div class="mt-auto flex flex-col gap-3">
            <div class="flex flex-row flex-wrap items-center gap-3">
                <span class="text-theme-2xl font-bold text-accent">
                    {{ tenant()->formatPrice($product->price) }}
                </span>
                @if($product->stock > 0)
                    <div class="badge bg-success/10 text-success">
                        متوفر ({{ $product->stock }})
                    </div>
                @else
                    <div class="badge bg-warning/10 text-warning">
                        نفذ
                    </div>
                @endif
            </div>

            <p class="text-muted text-theme-sm border-b border-border pb-4 mb-1 overflow-hidden">
                {{ $product->description ? Str::limit($product->description, 60) : 'لا يوجد وصف متاح لهذا المنتج حالياً.' }}
            </p>
            
            <div class="flex gap-2">
                <a href="{{ route('shop.product.show', ['id' => $product->id]) }}" 
                class="btn flex-1 text-center bg-surface-200 hover:bg-surface-300 text-theme text-theme-sm" wire:navigate>
                    عرض
                </a>

                <x-primary-button
                    wire:click="addToCart"
                    wire:loading.class="opacity-75 pointer-events-none"
                    wire:target="addToCart"
                    :disabled="$product->stock == 0"
                    class="grow-0 overflow-hidden text-theme-sm cursor-pointer px-2 py-2">
                    <span wire:loading.remove wire:target="addToCart">السلة</span>
                    <div wire:loading.remove wire:target="addToCart">
                        @icon('cart', 'w-4 h-4')
                    </div>

                    <div wire:loading wire:target="addToCart">
                        <p class="flex flex-row flex-nowrap items-center gap-1">
                            <x-spinner class="h-4 w-4" />
                            <span class="line-clamp-1">جاري الإضافة...</span>
                        </p>
                    </div>
                </x-primary-button>
            </div>
        </div>
    </div>
</div>