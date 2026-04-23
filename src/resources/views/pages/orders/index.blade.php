<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use Livewire\Attributes\Computed;

new class extends Component
{
    use WithPagination;

    #[Computed]
    public function orders()
    {
        return Order::with('items')
            ->where('customer_id', auth('customer')->id())
            ->where('status', '!=', 'draft')
            ->latest()
            ->paginate(10);
    }
};
?>

<x-slot name="top">
    <x-breadcrumbs :links="[
        __('Order History') => null,
    ]" />
</x-slot>

<div>
    <div class="flex flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-theme-2xl font-bold text-theme">{{ __('Order History') }}</h1>
        <a href="{{ route('shop.index', ['locale' => app()->getLocale()]) }}" wire:navigate class="btn bg-primary/10 text-primary">
            @icon('home', 'w-4 h-4')
            {{ __('Home') }}
        </a>
    </div>

    @if($this->orders->count() > 0)
        <div class="space-y-4">
            @foreach($this->orders as $order)
                @php
                    $statusColor = match($order->status->color()) {
                        'success' => 'bg-success/10 text-success',
                        'warning' => 'bg-warning/10 text-warning',
                        'danger'  => 'bg-danger/10 text-danger',
                        'primary' => 'bg-primary/10 text-primary',
                        'info'    => 'bg-info/10 text-info',
                        default   => 'bg-border text-muted',
                    };
                @endphp
                <div class="card p-5 flex flex-col sm:flex-row sm:items-center gap-4 justify-between" wire:key="order-{{ $order->id }}">
                    <div class="flex flex-col gap-1">
                        <div class="text-muted text-theme-xs">
                            {{ $order->created_at->format('d M Y, H:i') }}
                        </div>
                        <div class="text-muted text-theme-sm mt-1">
                            {{ trans_choice(':count item|:count items', $order->items->count(), ['count' => $order->items->count()]) }}
                        </div>
                    </div>

                    <div class="flex items-center gap-4 sm:gap-6 flex-wrap">
                        <div class="text-center">
                            <div class="text-muted text-theme-xs mb-1">{{ __('Total') }}</div>
                            <div class="font-black text-theme-lg text-accent">
                                {{ app(App\Services\Money\MoneyService::class)->formatOrderPrice($order, $order->total) }}
                            </div>
                        </div>

                        <span class="px-3 py-1 rounded-full text-theme-xs font-semibold {{ $statusColor }}">
                            {{ __($order->status->label()) }}
                        </span>

                        <a href="{{ route('shop.order.show', ['locale' => app()->getLocale(), 'id' => $order->id]) }}"
                           wire:navigate
                           class="btn bg-primary/10 text-primary text-theme-sm">
                            {{ __('View Details') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $this->orders->links() }}
        </div>
    @else
        <div class="p-16 text-center max-w-2xl mx-auto mt-10">
            <div class="w-fit mx-auto mb-6 opacity-60">
                @icon('bag', 'w-16 h-16')
            </div>
            <h2 class="text-theme-2xl font-bold text-theme mb-4">{{ __('No orders yet!') }}</h2>
            <p class="text-muted mb-8 text-theme-lg">{{ __('You have not placed any orders yet.') }}</p>
            <a href="{{ route('shop.products', ['locale' => app()->getLocale()]) }}" wire:navigate class="btn btn-primary">
                {{ __('Browse Products') }}
            </a>
        </div>
    @endif
</div>
