<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public function logout(): void
    {
        Auth::guard('customer')->logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirectRoute('shop.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return $this->view([
            'customer' => Auth::guard('customer')->user(),
        ]);
    }
};
?>

@php $customer = auth('customer')->user(); @endphp

@if($customer)
    {{-- Logged-in: Avatar + Dropdown --}}
    <div class="relative" x-data="{ open: false }" @click.outside="open = false">

        <button @click="open = !open"
                class="flex items-center rounded-theme-full cursor-pointer"
                :aria-expanded="open">
            <img
                src="{{ $customer->avatar_url }}"
                alt="{{ $customer->name }}"
                class="sm:w-[calc(var(--spacing-header-icon)+0.5rem)] sm:h-[calc(var(--spacing-header-icon)+0.5rem)] w-[calc(var(--spacing-m-header-icon)+0.5rem)] h-[calc(var(--spacing-m-header-icon)+0.5rem)] rounded-theme-full object-cover border-2 border-primary/20"
            >
        </button>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
             class="absolute top-full mt-2 inset-e-0 bg-card-bg border border-border rounded-card shadow-modal z-50 min-w-52 overflow-hidden origin-top-right"
             style="display: none;">

            {{-- Customer info header --}}
            <div class="px-4 py-3 border-b border-border bg-surface-100">
                <p class="text-theme-sm font-bold text-theme truncate">{{ $customer->name }}</p>
                <p class="text-theme-xs text-muted truncate">{{ $customer->email }}</p>
            </div>

            <div class="py-1">
                {{-- Account --}}
                {{-- <a href="{{ route('shop.account', ['locale' => app()->getLocale()]) }}"
                   wire:navigate
                   class="flex items-center gap-3 px-4 py-2.5 text-theme-sm text-theme hover:bg-surface-100 transition-colors">
                    @icon('user', 'w-4 h-4 text-muted')
                    {{ __('My Account') }}
                </a> --}}

                {{-- Orders --}}
                {{-- <a href="{{ route('shop.orders', ['locale' => app()->getLocale()]) }}"
                   wire:navigate
                   class="flex items-center gap-3 px-4 py-2.5 text-theme-sm text-theme hover:bg-surface-100 transition-colors">
                    @icon('bag', 'w-4 h-4 text-muted')
                    {{ __('My Orders') }}
                </a> --}}

                <div class="border-t border-border mt-1 pt-1">
                    <button wire:click="logout"
                            wire:loading.attr="disabled"
                            wire:target="logout"
                            class="w-full flex items-center gap-3 px-4 py-2.5 text-theme-sm text-danger hover:bg-danger/10 transition-colors cursor-pointer disabled:opacity-50">
                        <div wire:loading.remove wire:target="logout">
                            @icon('chevron-r', 'w-4 h-4 ltr:rotate-180')
                        </div>
                        <x-spinner wire:loading wire:target="logout" class="w-4 h-4" />
                        <span wire:loading.remove wire:target="logout">{{ __('Sign Out') }}</span>
                        <span wire:loading wire:target="logout">{{ __('Signing out...') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

@else
    {{-- Guest: Login + Signup buttons --}}
    <div class="flex items-center gap-2">
        <a href="{{ route('shop.login', ['locale' => app()->getLocale()]) }}"
           wire:navigate
           class="btn text-theme-sm sm:text-on-header text-on-m-header sm:border-border-input-header! border-border-input-m-header! sm:hover:border-on-header! hover:bordre-on-m-header! rounded-theme-full!">
            {{ __('Sign In') }}
        </a>
        <a href="{{ route('shop.signup', ['locale' => app()->getLocale()]) }}"
           wire:navigate
           class="btn btn-primary text-theme-sm shadow-glow! px-3 py-1.5 whitespace-nowrap">
            {{ __('Sign Up') }}
        </a>
    </div>
@endif