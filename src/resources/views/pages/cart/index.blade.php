<?php

use Livewire\Component;
use App\Services\CartService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

new class extends Component
{
    protected CartService $service;

    public function boot(CartService $service)
    {
        $this->service = $service;
    }

    #[Computed]
    public function cart()
    {
        return $this->service->getCart();
    }

    #[Computed]
    public function count()
    {
        return $this->service->getCount();
    }

    public function deleteItem(string $itemId)
    {
        $this->service->deleteItem($itemId);
        $this->dispatch('cart-updated');
    }

    public function decrementItem(string $itemId)
    {
        $this->service->decrementQuantity($itemId);
        $this->dispatch('cart-updated');
    }

    public function incrementItem(string $itemId)
    {
        $this->service->incrementQuantity($itemId);
        $this->dispatch('cart-updated');
    }
};
?>

<x-slot name="header">
</x-slot>

<div>
    <div class="flex flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-theme-2xl font-bold text-theme">{{ __('Cart') }}</h1>
        <a href="{{ route('shop.index') }}" wire:navigate class="btn bg-primary/10 text-primary">
            @icon('arrow-r', 'w-4 h-4')
            {{ __('Home') }}
        </a>
    </div>

    @if($this->count > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-4">
                @php
                    $totalPrice = 0;
                    $subtotals = [];
                @endphp

                @foreach($this->cart->items as $item)
                    @php 
                        $itemSubtotal = $item->unit_price * $item->quantity;
                        $totalPrice += $itemSubtotal;
                        $subtotals[] = $itemSubtotal;
                    @endphp
                    <div wire:key="cart-item-wrapper-{{ $item->id }}">
                        <x-cart-item :item="$item" :subtotal="$itemSubtotal" />
                    </div>
                @endforeach
            </div>

            <div class="card p-5 h-fit sticky top-10">
                <h2 class="text-theme-xl font-bold text-theme mb-6 border-b border-border pb-4">{{ __('Order Summary') }}</h2>

                <div class="space-y-2 mb-6 text-muted text-theme-sm">
                    @foreach($subtotals as $subtotal)
                        <div class="flex justify-between">
                            <span>{{ __('Subtotal') }}:</span>
                            <span class="font-bold text-theme">
                                {{ app(App\Services\Money\MoneyService::class)->format($subtotal) }}+
                            </span>
                        </div>
                    @endforeach
                    <div class="flex justify-between">
                        <span>{{ __('Shipping') }}:</span>
                        <span class="font-bold text-success">{{ __('Free') }}</span>
                    </div>
                </div>

                <div class="border-t pt-4 mb-8 border-border">
                    <div class="flex justify-between items-center">
                        <span class="text-theme-lg font-bold text-theme">{{ __('Total') }}:</span>
                        <span class="text-theme-2xl font-black text-accent">
                            {{ app(App\Services\Money\MoneyService::class)->format($totalPrice) }}
                        </span>
                    </div>
                </div>

                <x-primary-button>
                    <span>{{ __('Checkout') }}</span>
                    @icon('card', 'w-5 h-5')
                </x-primary-button>
            </div>
        </div>
    @else
        <div class="p-16 text-center max-w-2xl mx-auto mt-10">
            <div class="text-theme-7xl w-fit mx-auto mb-6 opacity-80">
                @icon('cart', 'w-14 h-14')
            </div>
            <h2 class="text-theme-2xl font-bold text-theme mb-4">{{ __('Your cart is empty!') }}</h2>
            <p class="text-muted mb-8 text-theme-lg">{{ __('You have not added any products to your cart yet.') }}</p>
            <a href="{{ route('shop.products') }}" class="btn btn-primary">
                {{ __('Browse Products') }}
            </a>
        </div>
    @endif
</div>