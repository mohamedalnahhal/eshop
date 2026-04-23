<?php

use Livewire\Component;
use App\Services\CartService;
use App\Services\Shipping\ShippingCalculatorService;
use App\Services\Money\MoneyService;
use App\Enums\AddressType;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    protected CartService $cartService;
    protected ShippingCalculatorService $shippingService;
    protected MoneyService $moneyService;

    public ?string $selectedAddressId      = null;
    public bool    $showAddressForm        = false;
    public bool    $guestAddressSaved      = false;

    // Address form fields
    public string $addr_name    = '';
    public string $addr_line1   = '';
    public string $addr_line2   = '';
    public string $addr_city    = '';
    public string $addr_state   = '';
    public string $addr_postal  = '';
    public string $addr_country = '';

    public ?string $selectedShippingMethodId = null;

    public function boot(
        CartService $cartService,
        ShippingCalculatorService $shippingService,
        MoneyService $moneyService,
    ): void {
        $this->cartService     = $cartService;
        $this->shippingService = $shippingService;
        $this->moneyService    = $moneyService;
    }

    public function mount(): void
    {
        if ($this->cartService->getCount() === 0) {
            $this->redirect(route('shop.cart', ['locale' => app()->getLocale()]));
            return;
        }

        $customer = Auth::guard('customer')->user();

        if ($customer) {
            $addresses = $customer->addresses()->get();
            if ($addresses->isNotEmpty()) {
                $default = $addresses->firstWhere('is_default', true) ?? $addresses->first();
                $this->selectedAddressId = $default->id;
            } else {
                $this->showAddressForm = true;
            }
        } else {
            $this->showAddressForm = true;
        }
    }

    #[Computed]
    public function cart()
    {
        return $this->cartService->getCart()?->load(['items.product.media']);
    }

    #[Computed]
    public function addresses()
    {
        $customer = Auth::guard('customer')->user();
        return $customer ? $customer->addresses()->get() : collect();
    }

    #[Computed]
    public function selectedAddress()
    {
        if (! $this->selectedAddressId) {
            return null;
        }
        return $this->addresses->firstWhere('id', $this->selectedAddressId);
    }

    #[Computed]
    public function shippingMethods()
    {
        $country = $this->resolveCountryForShipping();

        if (! $country) {
            return collect();
        }

        $cart = $this->cartService->getCart()?->load(['items.product']);

        if (! $cart || $cart->items->isEmpty()) {
            return collect();
        }

        return $this->shippingService->getAvailableMethods($cart, $country);
    }

    #[Computed]
    public function selectedMethod()
    {
        if (! $this->selectedShippingMethodId) {
            return null;
        }
        return $this->shippingMethods->firstWhere('id', $this->selectedShippingMethodId);
    }

    #[Computed]
    public function hasAddressForShipping(): bool
    {
        return (bool) $this->resolveCountryForShipping();
    }

    #[Computed]
    public function orderSubtotal(): int
    {
        return $this->cartService->getTotal();
    }

    #[Computed]
    public function shippingFee(): int
    {
        return $this->selectedMethod?->fee ?? 0;
    }

    #[Computed]
    public function orderTotal(): int
    {
        return $this->orderSubtotal + $this->shippingFee;
    }

    #[Computed]
    public function countries(): array
    {
        return [
            'SA' => __('Saudi Arabia'),
            'AE' => __('United Arab Emirates'),
            'KW' => __('Kuwait'),
            'QA' => __('Qatar'),
            'BH' => __('Bahrain'),
            'OM' => __('Oman'),
            'JO' => __('Jordan'),
            'EG' => __('Egypt'),
            'IQ' => __('Iraq'),
            'SY' => __('Syria'),
            'LB' => __('Lebanon'),
            'YE' => __('Yemen'),
            'MA' => __('Morocco'),
            'TN' => __('Tunisia'),
            'DZ' => __('Algeria'),
            'LY' => __('Libya'),
            'SD' => __('Sudan'),
            'PS' => __('Palestine'),
            'US' => __('United States'),
            'GB' => __('United Kingdom'),
            'DE' => __('Germany'),
            'FR' => __('France'),
            'IT' => __('Italy'),
            'ES' => __('Spain'),
            'NL' => __('Netherlands'),
            'BE' => __('Belgium'),
            'CH' => __('Switzerland'),
            'AT' => __('Austria'),
            'SE' => __('Sweden'),
            'NO' => __('Norway'),
            'DK' => __('Denmark'),
            'PL' => __('Poland'),
            'TR' => __('Turkey'),
            'PK' => __('Pakistan'),
            'IN' => __('India'),
            'BD' => __('Bangladesh'),
            'CN' => __('China'),
            'JP' => __('Japan'),
            'KR' => __('South Korea'),
            'MY' => __('Malaysia'),
            'SG' => __('Singapore'),
            'AU' => __('Australia'),
            'CA' => __('Canada'),
            'ZA' => __('South Africa'),
        ];
    }

    public function selectAddress(string $id): void
    {
        $this->selectedAddressId        = $id;
        $this->showAddressForm          = false;
        $this->selectedShippingMethodId = null;
    }

    public function showNewAddressForm(): void
    {
        $this->selectedAddressId        = null;
        $this->showAddressForm          = true;
        $this->guestAddressSaved        = false;
        $this->selectedShippingMethodId = null;
    }

    public function saveAddress(): void
    {
        $this->validate([
            'addr_name'    => 'required|string|max:100',
            'addr_line1'   => 'required|string|max:255',
            'addr_line2'   => 'nullable|string|max:255',
            'addr_city'    => 'required|string|max:100',
            'addr_state'   => 'nullable|string|max:100',
            'addr_postal'  => 'nullable|string|max:20',
            'addr_country' => 'required|string|max:3',
        ]);

        $customer = Auth::guard('customer')->user();

        if ($customer) {
            $isFirst = $customer->addresses()->count() === 0;

            $address = $customer->addresses()->create([
                'name'        => $this->addr_name,
                'type'        => AddressType::SHIPPING,
                'line_1'      => $this->addr_line1,
                'line_2'      => $this->addr_line2 ?: null,
                'city'        => $this->addr_city,
                'state'       => $this->addr_state ?: null,
                'postal_code' => $this->addr_postal ?: null,
                'country'     => strtoupper($this->addr_country),
                'is_default'  => $isFirst,
            ]);

            $this->selectedAddressId = $address->id;
        }

        $this->showAddressForm          = false;
        $this->guestAddressSaved        = true;
        $this->selectedShippingMethodId = null;
    }

    public function selectShippingMethod(string $id): void
    {
        $this->selectedShippingMethodId = $id;
    }

    public function updatedAddrCountry(): void
    {
        $this->selectedShippingMethodId = null;
    }

    private function resolveCountryForShipping(): ?string
    {
        if ($this->selectedAddressId && $this->selectedAddress) {
            return $this->selectedAddress->country;
        }

        if (! $this->showAddressForm && $this->addr_country) {
            return strtoupper($this->addr_country);
        }

        return null;
    }
};
?>

<x-slot name="header">
</x-slot>

<div>
    {{-- Page heading --}}
    <div class="flex flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-theme-2xl font-bold text-theme">{{ __('Checkout') }}</h1>
        <a href="{{ route('shop.cart', ['locale' => app()->getLocale()]) }}"
           wire:navigate
           class="btn bg-primary/10 text-primary">
            @icon('arrow-r', 'w-4 h-4 ltr:rotate-180')
            {{ __('Back to Cart') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- ═══════════════ LEFT COLUMN — Steps ═══════════════ --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- ─── 1. Order Details ─── --}}
            <div class="card p-6">
                <h2 class="flex items-center gap-2 text-theme-xl font-bold text-theme mb-5 pb-4 border-b border-border">
                    @icon('order', 'w-5 h-5 text-primary shrink-0')
                    {{ __('Order Details') }}
                </h2>

                <div class="divide-y divide-border">
                    @foreach($this->cart->items as $item)
                        @php $imagePath = $item->product->media->first()?->file_path; @endphp
                        <div class="flex items-center gap-4 py-3 first:pt-0 last:pb-0">

                            {{-- Thumbnail --}}
                            <div class="w-16 h-16 shrink-0 rounded-[calc(var(--radius-card)-0.25rem)] overflow-hidden bg-surface-200 flex items-center justify-center">
                                @if($imagePath)
                                    <img src="{{ asset('storage/' . $imagePath) }}"
                                         alt="{{ $item->product->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    @icon('image', 'w-6 h-6 text-muted')
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="grow min-w-0">
                                <p class="font-semibold text-theme line-clamp-1">{{ $item->product->name }}</p>
                                <p class="text-theme-sm text-muted mt-0.5">
                                    {{ __('Qty') }}: {{ $item->quantity }}
                                    &nbsp;·&nbsp;
                                    {{ __('Unit') }}: {{ app(App\Services\Money\MoneyService::class)->format($item->product->price) }}
                                </p>
                            </div>

                            {{-- Subtotal --}}
                            <span class="font-bold text-theme shrink-0">
                                {{ app(App\Services\Money\MoneyService::class)->format($item->subtotal()) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ─── 2. Shipping Address ─── --}}
            <div class="card p-6">
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-border gap-4">
                    <h2 class="flex items-center gap-2 text-theme-xl font-bold text-theme">
                        @icon('home', 'w-5 h-5 text-primary shrink-0')
                        {{ __('Shipping Address') }}
                    </h2>

                    {{-- "New address" button when showing saved address cards --}}
                    @if(!$showAddressForm && $this->addresses->isNotEmpty())
                        <button type="button"
                                wire:click="showNewAddressForm"
                                class="shrink-0 btn border border-border-input hover:bg-surface-100 text-theme-sm text-theme gap-1.5 transition">
                            @icon('pen', 'w-3.5 h-3.5')
                            {{ __('New Address') }}
                        </button>
                    @endif
                </div>

                {{-- Saved address cards (authenticated users with saved addresses) --}}
                @if(!$showAddressForm && $this->addresses->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($this->addresses as $address)
                            <button type="button"
                                    wire:click="selectAddress('{{ $address->id }}')"
                                    wire:key="addr-{{ $address->id }}"
                                    class="text-start p-4 rounded-theme-md border-2 transition cursor-pointer w-full
                                           {{ $selectedAddressId === $address->id
                                               ? 'border-primary bg-primary/5'
                                               : 'border-border hover:border-primary/40 hover:bg-surface-100' }}">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="font-bold text-theme text-theme-sm truncate">{{ $address->name }}</p>
                                        <p class="text-muted text-theme-xs mt-1.5 leading-relaxed">
                                            {{ $address->line_1 }}
                                            @if($address->line_2), {{ $address->line_2 }}@endif<br>
                                            {{ $address->city }}
                                            @if($address->state), {{ $address->state }}@endif
                                            @if($address->postal_code) {{ $address->postal_code }}@endif<br>
                                            {{ $address->country }}
                                        </p>
                                    </div>
                                    @if($selectedAddressId === $address->id)
                                        <span class="shrink-0 text-primary mt-0.5">
                                            @icon('check', 'w-5 h-5')
                                        </span>
                                    @endif
                                </div>
                            </button>
                        @endforeach
                    </div>

                {{-- Guest: address saved state — show summary + edit button --}}
                @elseif(!$showAddressForm && $guestAddressSaved)
                    <div class="p-4 rounded-theme-md border-2 border-primary bg-primary/5 flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="font-bold text-theme text-theme-sm">{{ $addr_name }}</p>
                            <p class="text-muted text-theme-xs mt-1.5 leading-relaxed">
                                {{ $addr_line1 }}@if($addr_line2), {{ $addr_line2 }}@endif<br>
                                {{ $addr_city }}@if($addr_state), {{ $addr_state }}@endif @if($addr_postal){{ $addr_postal }}@endif<br>
                                {{ strtoupper($addr_country) }}
                            </p>
                        </div>
                        <button type="button"
                                wire:click="showNewAddressForm"
                                class="shrink-0 btn border border-border-input hover:bg-surface-100 text-theme-sm text-theme gap-1.5 transition">
                            @icon('pen', 'w-3.5 h-3.5')
                            {{ __('Edit') }}
                        </button>
                    </div>
                @endif

                {{-- Address form (new / guest / no saved addresses) --}}
                @if($showAddressForm)
                    <div class="space-y-4">

                        {{-- Login nudge for guests --}}
                        @guest('customer')
                            <div class="bg-primary/5 border border-primary/20 rounded-theme-md px-4 py-3 text-theme-sm text-muted">
                                {{ __('Have an account?') }}
                                <a href="{{ route('shop.login', ['locale' => app()->getLocale()]) }}"
                                   class="text-primary font-semibold hover:underline ms-1">{{ __('Sign in') }}</a>
                                {{ __('to save your addresses.') }}
                            </div>
                        @endguest

                        {{-- Name --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-theme-sm font-semibold text-theme">
                                {{ __('Full Name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   wire:model="addr_name"
                                   placeholder="{{ __('e.g. Home, Work …') }}"
                                   class="input w-full {{ $errors->has('addr_name') ? 'border-danger!' : '' }}">
                            @error('addr_name')
                                <span class="text-danger text-theme-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Address Line 1 --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-theme-sm font-semibold text-theme">
                                {{ __('Address Line 1') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   wire:model="addr_line1"
                                   placeholder="{{ __('Street, building …') }}"
                                   class="input w-full {{ $errors->has('addr_line1') ? 'border-danger!' : '' }}">
                            @error('addr_line1')
                                <span class="text-danger text-theme-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Address Line 2 --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-theme-sm font-semibold text-theme">
                                {{ __('Address Line 2') }}
                                <span class="text-muted font-normal">({{ __('optional') }})</span>
                            </label>
                            <input type="text"
                                   wire:model="addr_line2"
                                   placeholder="{{ __('Apartment, suite …') }}"
                                   class="input w-full {{ $errors->has('addr_line2') ? 'border-danger!' : '' }}">
                            @error('addr_line2')
                                <span class="text-danger text-theme-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- City + State --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-theme-sm font-semibold text-theme">
                                    {{ __('City') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       wire:model="addr_city"
                                       placeholder="{{ __('City') }}"
                                       class="input w-full {{ $errors->has('addr_city') ? 'border-danger!' : '' }}">
                                @error('addr_city')
                                    <span class="text-danger text-theme-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-theme-sm font-semibold text-theme">
                                    {{ __('State / Province') }}
                                    <span class="text-muted font-normal">({{ __('optional') }})</span>
                                </label>
                                <input type="text"
                                       wire:model="addr_state"
                                       placeholder="{{ __('State') }}"
                                       class="input w-full {{ $errors->has('addr_state') ? 'border-danger!' : '' }}">
                                @error('addr_state')
                                    <span class="text-danger text-theme-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Postal Code + Country --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-theme-sm font-semibold text-theme">
                                    {{ __('Postal Code') }}
                                    <span class="text-muted font-normal">({{ __('optional') }})</span>
                                </label>
                                <input type="text"
                                       wire:model="addr_postal"
                                       placeholder="{{ __('12345') }}"
                                       class="input w-full {{ $errors->has('addr_postal') ? 'border-danger!' : '' }}">
                                @error('addr_postal')
                                    <span class="text-danger text-theme-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-theme-sm font-semibold text-theme">
                                    {{ __('Country') }} <span class="text-danger">*</span>
                                </label>
                                <select wire:model.live="addr_country"
                                        class="input w-full {{ $errors->has('addr_country') ? 'border-danger!' : '' }}">
                                    <option value="">— {{ __('Select Country') }} —</option>
                                    @foreach($this->countries as $code => $label)
                                        <option value="{{ $code }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('addr_country')
                                    <span class="text-danger text-theme-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Save address button --}}
                        <div class="pt-2">
                            <button type="button"
                                    wire:click="saveAddress"
                                    wire:loading.attr="disabled"
                                    wire:target="saveAddress"
                                    class="btn btn-primary px-6 flex items-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed">
                                <x-spinner wire:loading wire:target="saveAddress" class="w-4 h-4" />
                                <span wire:loading.remove wire:target="saveAddress">
                                    @icon('check', 'w-4 h-4')
                                </span>
                                {{ __('Save Address') }}
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ─── 3. Shipping Method ─── --}}
            @if($this->shippingMethods->isNotEmpty())
                <div class="card p-6">
                    <h2 class="flex items-center gap-2 text-theme-xl font-bold text-theme mb-5 pb-4 border-b border-border">
                        @icon('truck', 'w-5 h-5 text-primary shrink-0')
                        {{ __('Shipping Method') }}
                    </h2>

                    <div class="space-y-3">
                        @foreach($this->shippingMethods as $method)
                            <button type="button"
                                    wire:click="selectShippingMethod('{{ $method->id }}')"
                                    wire:key="method-{{ $method->id }}"
                                    class="w-full text-start p-4 rounded-theme-md border-2 transition cursor-pointer
                                           {{ $selectedShippingMethodId === $method->id
                                               ? 'border-primary bg-primary/5'
                                               : 'border-border hover:border-primary/40 hover:bg-surface-100' }}">
                                <div class="flex items-center justify-between gap-4">

                                    {{-- Radio indicator + name/description --}}
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="shrink-0 w-5 h-5 rounded-full border-2 flex items-center justify-center transition
                                                    {{ $selectedShippingMethodId === $method->id
                                                        ? 'border-primary bg-primary'
                                                        : 'border-border' }}">
                                            @if($selectedShippingMethodId === $method->id)
                                                <div class="w-2 h-2 rounded-full bg-white"></div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-theme">{{ $method->name }}</p>
                                            @if($method->description)
                                                <p class="text-muted text-theme-xs mt-0.5">{{ $method->description }}</p>
                                            @endif
                                            @if($method->estimatedDelivery)
                                                <p class="text-muted text-theme-xs mt-0.5">
                                                    {{ __('Est. delivery') }}: {{ $method->estimatedDelivery }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Fee --}}
                                    @if($method->isFree)
                                        <span class="shrink-0 font-bold text-success">{{ __('Free') }}</span>
                                    @else
                                        <span class="shrink-0 font-bold text-theme">
                                            {{ app(App\Services\Money\MoneyService::class)->format($method->fee) }}
                                        </span>
                                    @endif
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

            @elseif($this->hasAddressForShipping)
                {{-- Address selected but no shipping methods available for this country --}}
                <div class="card p-6">
                    <h2 class="flex items-center gap-2 text-theme-xl font-bold text-theme mb-4 pb-4 border-b border-border">
                        @icon('truck', 'w-5 h-5 text-primary shrink-0')
                        {{ __('Shipping Method') }}
                    </h2>
                    <p class="text-muted text-theme-sm">
                        {{ __('No shipping methods are available for the selected address. Please contact us for assistance.') }}
                    </p>
                </div>
            @endif

        </div>

        {{-- ═══════════════ RIGHT COLUMN — Order Summary ═══════════════ --}}
        <div class="card p-5 h-fit sticky top-header-hm">
            <h2 class="text-theme-xl font-bold text-theme mb-6 pb-4 border-b border-border">
                {{ __('Order Summary') }}
            </h2>

            {{-- Items list --}}
            <div class="space-y-2 mb-4 text-theme-sm">
                @foreach($this->cart->items as $item)
                    <div class="flex justify-between gap-2 text-muted">
                        <span class="truncate">
                            {{ $item->product->name }}
                            <span class="text-theme-xs opacity-60">×{{ $item->quantity }}</span>
                        </span>
                        <span class="font-bold text-theme shrink-0">
                            {{ app(App\Services\Money\MoneyService::class)->format($item->subtotal()) }}
                        </span>
                    </div>
                @endforeach
            </div>

            {{-- Subtotal + Shipping rows --}}
            <div class="border-t border-border pt-3 space-y-2 text-theme-sm text-muted mb-4">
                <div class="flex justify-between">
                    <span>{{ __('Subtotal') }}:</span>
                    <span class="font-bold text-theme">
                        {{ app(App\Services\Money\MoneyService::class)->format($this->orderSubtotal) }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>{{ __('Shipping') }}:</span>
                    @if($this->selectedMethod)
                        @if($this->selectedMethod->isFree)
                            <span class="font-bold text-success">{{ __('Free') }}</span>
                        @else
                            <span class="font-bold text-theme">
                                {{ app(App\Services\Money\MoneyService::class)->format($this->shippingFee) }}
                            </span>
                        @endif
                    @else
                        <span class="italic text-theme-xs">—</span>
                    @endif
                </div>
            </div>

            {{-- Total --}}
            <div class="border-t border-border pt-4 mb-4">
                <div class="flex justify-between items-center">
                    <span class="text-theme-lg font-bold text-theme">{{ __('Total') }}:</span>
                    <span class="text-theme-2xl font-black text-accent">
                        {{ app(App\Services\Money\MoneyService::class)->format($this->orderTotal) }}
                    </span>
                </div>
                @if(!$this->selectedMethod)
                    <p class="text-theme-xs text-muted mt-1 text-end">
                        {{ __('* Select a shipping method to see final total') }}
                    </p>
                @endif
            </div>

            {{-- Selected method recap --}}
            @if($this->selectedMethod)
                <div class="bg-surface-100 rounded-theme-md px-3 py-2.5 text-theme-sm text-muted mb-5 flex items-center gap-2">
                    @icon('truck', 'w-4 h-4 shrink-0 text-primary')
                    <span class="truncate">{{ $this->selectedMethod->name }}</span>
                    @if($this->selectedMethod->estimatedDelivery)
                        <span class="ms-auto shrink-0 text-theme-xs opacity-70">
                            {{ $this->selectedMethod->estimatedDelivery }}
                        </span>
                    @endif
                </div>
            @else
                <div class="mb-5"></div>
            @endif

            {{-- Pay / Confirm (not active) --}}
            <x-primary-button
                type="button"
                disabled
                title="{{ __('Complete all steps to proceed') }}">
                @icon('card', 'w-5 h-5')
                <span>{{ __('Pay / Confirm') }}</span>
            </x-primary-button>

            @if(!$this->selectedMethod || (!$selectedAddressId && !$guestAddressSaved))
                <p class="text-theme-xs text-muted text-center mt-3">
                    {{ __('Please fill in your address and choose a shipping method.') }}
                </p>
            @endif
        </div>

    </div>
</div>
