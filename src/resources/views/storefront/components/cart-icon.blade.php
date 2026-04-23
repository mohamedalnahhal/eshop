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

<a href="{{ url(app()->getLocale() . '/cart') }}" class="relative inline-block rounded-theme-full hover:bg-surface-100 p-2 transition-colors" wire:navigate>
    @icon('cart', 'sm:w-header-icon sm:h-header-icon w-m-header-icon h-m-header-icon sm:text-on-header text-on-m-header')
    <span wire:loading.class="animate-pulse bg-red-400"
        wire:loading.class.remove="bg-red-600"
        wire:target="updateCartCount"
        class="absolute top-1 right-1 inline-flex items-center justify-center px-2 py-1 text-theme-xs font-bold leading-none text-bg transform translate-x-1/2 -translate-y-1/2 bg-danger rounded-theme-full">
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