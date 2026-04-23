<?php

use Livewire\Component;
use App\Models\Order;
use Livewire\Attributes\Computed;

new class extends Component
{
    public Order $order;

    public function mount(string $id)
    {
        $this->order = Order::with(['items.product.media'])
            ->where('customer_id', auth('customer')->id())
            ->where('status', '!=', 'draft')
            ->findOrFail($id);
    }
};
?>

<x-slot name="top">
    <x-breadcrumbs :links="[
        __('Order History') => route('shop.orders', ['locale' => app()->getLocale()]),
        strtoupper(substr($this->order->tracking_number, 9)) => null,
    ]" />
</x-slot>

<div>
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-theme-2xl font-bold text-theme">{{ __('Order Details') }}</h1>
            <p class="text-muted text-theme-sm mt-1">
                <span dir="ltr" class="text-theme rtl:text-end">
                    {{ '#' . strtoupper(substr($this->order->tracking_number, 9)) }}
                </span>
                &middot;
                {{ $this->order->created_at->format('d M Y, H:i') }}
            </p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            @php
                $statusColor = match($this->order->status->color()) {
                    'success' => 'bg-success/10 text-success',
                    'warning' => 'bg-warning/10 text-warning',
                    'danger'  => 'bg-danger/10 text-danger',
                    'primary' => 'bg-primary/10 text-primary',
                    'info'    => 'bg-info/10 text-info',
                    default   => 'bg-border text-muted',
                };
            @endphp
            <span class="px-4 py-1.5 rounded-full text-theme-sm font-semibold {{ $statusColor }}">
                {{ __($this->order->status->label()) }}
            </span>
            <a href="{{ route('shop.orders', ['locale' => app()->getLocale()]) }}" wire:navigate class="btn bg-primary/10 text-primary">
                @icon('arrow-r', 'w-4 h-4')
                {{ __('Order History') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left: Items + Shipping --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Order Items --}}
            <div class="card p-5">
                <h2 class="text-theme-lg font-bold text-theme mb-4 pb-4 border-b border-border">{{ __('Order Items') }}</h2>
                <div class="divide-y divide-border">
                    @foreach($this->order->items as $item)
                        @php
                            $locale = app()->getLocale();
                            $productName = $item->product_name[$locale]
                                ?? $item->product_name['en']
                                ?? collect($item->product_name)->first()
                                ?? __('Product');
                            $image = $item->product?->media?->first();
                        @endphp
                        <div class="py-4 flex items-center gap-4" wire:key="item-{{ $item->id }}">
                            @if($image)
                                <img src="{{ asset('storage/' . $image->file_path) }}"
                                     alt="{{ $productName }}"
                                     class="w-16 h-16 object-cover rounded-theme-sm shrink-0" />
                            @else
                                <div class="w-16 h-16 bg-border rounded-theme-sm shrink-0 flex items-center justify-center text-muted">
                                    @icon('bag', 'w-6 h-6')
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-theme truncate">{{ $productName }}</p>
                                <p class="text-muted text-theme-sm mt-0.5">
                                    {{ __('Qty') }}: {{ $item->quantity }}
                                    &middot;
                                    {{ app(App\Services\Money\MoneyService::class)->formatOrderPrice($this->order, $item->unit_price) }} / {{ __('unit') }}
                                </p>
                            </div>

                            <div class="font-bold text-theme text-theme-base shrink-0">
                                {{ app(App\Services\Money\MoneyService::class)->formatOrderPrice($this->order, $item->total) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Shipping Address --}}
            @if($this->order->shipping_address)
                <div class="card p-5">
                    <h2 class="text-theme-lg font-bold text-theme mb-4 pb-4 border-b border-border">{{ __('Shipping Address') }}</h2>
                    <div class="text-muted text-theme-sm space-y-1">
                        @if(!empty($this->order->shipping_address['name']))
                            <p class="font-semibold text-theme">{{ $this->order->shipping_address['name'] }}</p>
                        @endif
                        @if(!empty($this->order->shipping_address['address']))
                            <p>{{ $this->order->shipping_address['address'] }}</p>
                        @endif
                        @if(!empty($this->order->shipping_address['city']) || !empty($this->order->shipping_address['state']))
                            <p>{{ implode(', ', array_filter([$this->order->shipping_address['city'] ?? null, $this->order->shipping_address['state'] ?? null])) }}</p>
                        @endif
                        @if(!empty($this->order->shipping_address['country']))
                            <p>{{ $this->order->shipping_address['country'] }}</p>
                        @endif
                        @if(!empty($this->order->shipping_address['phone']))
                            <p>{{ $this->order->shipping_address['phone'] }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Right: Order Summary --}}
        <div class="card p-5 h-fit sticky top-header-hm">
            <h2 class="text-theme-xl font-bold text-theme mb-6 border-b border-border pb-4">{{ __('Order Summary') }}</h2>

            <div class="space-y-3 mb-6 text-theme-sm">
                <div class="flex justify-between text-muted">
                    <span>{{ __('Subtotal') }}:</span>
                    <span class="font-semibold text-theme">
                        {{ app(App\Services\Money\MoneyService::class)->formatOrderPrice($this->order, $this->order->subtotal) }}
                    </span>
                </div>

                <div class="flex justify-between text-muted">
                    <span>{{ __('Shipping') }}:</span>
                    <span class="font-semibold {{ $this->order->shipping_fees > 0 ? 'text-theme' : 'text-success' }}">
                        @if($this->order->shipping_fees > 0)
                            {{ app(App\Services\Money\MoneyService::class)->formatOrderPrice($this->order, $this->order->shipping_fees) }}
                        @else
                            {{ __('Free') }}
                        @endif
                    </span>
                </div>

                @if($this->order->discount > 0)
                    <div class="flex justify-between text-muted">
                        <span>{{ __('Discount') }}:</span>
                        <span class="font-semibold text-success">
                            - {{ app(App\Services\Money\MoneyService::class)->formatOrderPrice($this->order, $this->order->discount) }}
                        </span>
                    </div>
                @endif
            </div>

            <div class="border-t pt-4 border-border">
                <div class="flex justify-between items-center">
                    <span class="text-theme-lg font-bold text-theme">{{ __('Total') }}:</span>
                    <span class="text-theme-2xl font-black text-accent">
                        {{ app(App\Services\Money\MoneyService::class)->formatOrderPrice($this->order, $this->order->total) }}
                    </span>
                </div>
            </div>

            @if($this->order->notes)
                <div class="mt-6 pt-4 border-t border-border">
                    <h3 class="text-theme-sm font-semibold text-theme mb-2">{{ __('Notes') }}</h3>
                    <p class="text-muted text-theme-sm">{{ $this->order->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
