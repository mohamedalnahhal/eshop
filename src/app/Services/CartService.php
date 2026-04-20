<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Str;

class CartService
{
    private const GUEST_TOKEN_KEY = 'guest_cart_token';

    public function getCart(): ?Cart
    {
        if (Auth::guard('customer')->check()) {
            return $this->getCustomerCart();
        }
 
        if ($this->guestCheckoutEnabled()) {
            return $this->getGuestCart();
        }
 
        return null;
    }

    private function getCustomerCart(): Cart
    {
        return Cart::firstOrCreate(
            [
                'customer_id' => Auth::guard('customer')->id(),
            ],
            [
                'session_token' => null,
                'expires_at'    => null,
            ]
        );
    }

    private function getGuestCart(): Cart
    {
        $token = session()->get(self::GUEST_TOKEN_KEY);
 
        if (! $token) {
            $token = (string) Str::uuid();
            session()->put(self::GUEST_TOKEN_KEY, $token);
        }
 
        return Cart::firstOrCreate(
            [
                'session_token' => $token,
                'customer_id'   => null,
            ],
            [
                'expires_at' => now()->addDays(30),
            ]
        );
    }

    public function guestCheckoutEnabled(): bool
    {
        return (bool) (tenant()->settings?->guest_checkout_enabled ?? true);
    }

    public function canUseCart(): bool
    {
        return Auth::guard('customer')->check() || $this->guestCheckoutEnabled();
    }

    public function mergeGuestIntoCustomer(Session $session): void
    {        
        $token = $this->getGuestToken();

        if (! $token) {
            return;
        }

        $guestCart = Cart::with('items')
                         ->where('session_token', $token)
                         ->whereNull('customer_id')
                         ->first();
        
        if (! $guestCart || $guestCart->items->isEmpty()) {
            $session->forget(self::GUEST_TOKEN_KEY);
            return;
        }
 
        $customerCart = $this->getCustomerCart();
 
        DB::transaction(function () use ($guestCart, $customerCart, $session) {
            foreach ($guestCart->items as $guestItem) {
                $existing = $customerCart->items()
                                         ->where('product_id', $guestItem->product_id)
                                         ->first();
 
                if ($existing) {
                    $existing->increment('quantity', $guestItem->quantity);
                    $guestItem->delete();
                } else {
                    $guestItem->update(['cart_id' => $customerCart->id]);
                }
            }
 
            $guestCart->delete();
            $session->forget(self::GUEST_TOKEN_KEY);
        });
    }
    
    public function add(string $productId, int $quantity = 1): void
    {
        $cart = $this->getCart();

        if (! $cart) {
            return;
        }

        $product = Product::findOrFail($productId);

        $cartItem = $cart->items()
                         ->where('product_id', $productId)
                         ->first();

        $currentQty = $cartItem ? $cartItem->quantity : 0;

        if ($product->stock < $currentQty + $quantity) {
            throw new InsufficientStockException($product->stock);
        }

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity'   => $quantity,
            ]);
        }
    }

    public function incrementItem(string $cartItemId, int $amount = 1): void
    {
        $cart = $this->getCart();

        if (! $cart) {
            return;
        }

        $cartItem = $cart->items()->with('product')->find($cartItemId);

        if (! $cartItem) {
            return;
        }

        if ($cartItem->product->stock < $cartItem->quantity + $amount) {
            throw new InsufficientStockException($cartItem->product->stock);
        }

        $cartItem->increment('quantity', $amount);
    }

    public function decrementItem(string $cartItemId, int $amount = 1): void
    {
        $cart = $this->getCart();
 
        if (! $cart) {
            return;
        }
 
        $cartItem = $cart->items()->find($cartItemId);
 
        if (! $cartItem) {
            return;
        }
 
        if ($cartItem->quantity > $amount) {
            $cartItem->decrement('quantity', $amount);
        } else {
            $this->deleteItem($cartItemId);
        }
    }
 
    public function deleteItem(string $cartItemId): void
    {
        $cart = $this->getCart();
 
        if ($cart) {
            $cart->items()->where('id', $cartItemId)->delete();
        }
    }
 
    public function clear(): void
    {
        $this->getCart()?->items()->delete();
    }

    public function getCount(): int
    {
        return $this->getCart()?->items()->sum('quantity') ?? 0;
    }
 
    public function getTotal(): int
    {
        return (int) ($this->getCart()?->total() ?? 0);
    }

    public function getGuestToken(): ?string
    {
        return session()->get(self::GUEST_TOKEN_KEY);
    }
}