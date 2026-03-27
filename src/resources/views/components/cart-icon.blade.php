<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Services\CartService;

new class extends Component
{
    protected CartService $service;

    public function boot(CartService $service)
    {
        $this->service = $service;
    }

    #[Computed]
    public function count()
    {
        return $this->service->getCount();
    }

    public function updateCartCount() {}
};
?>

<a href="\cart" class="relative inline-block rounded-full hover:bg-gray-200 p-2" wire:navigate>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
    </svg>
    <span wire:loading.class="animate-pulse bg-red-400"
        wire:loading.class.remove="bg-red-600"
        wire:target="updateCartCount"
        class="absolute top-1 right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
        {{ $this->count }} 
    </span>
</a>

@script
<script>
    document.addEventListener('livewire:navigated', () => {
        $wire.$refresh(); 
    });

    Livewire.on('cart-updated', () => {
        $wire.updateCartCount();
    });
</script>
@endscript