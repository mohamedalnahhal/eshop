<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartService
{
    // TODO: Implement proper auth

    public function getCart(): ?Cart
    {
        if (!Auth::check()) {
            return null;
        }

        return Cart::with('items.product')->firstOrCreate(
            ['user_id' => Auth::id()]
        );
    }

    public function add(string $productId, int $quantity = 1): void
    {
        $product = Product::findOrFail($productId);
        $cart = $this->getCart();

        if (!$cart) return;

        $cartItem = $cart->items()
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }
    }

    public function remove(string $cartItemId): void
    {
        $cart = $this->getCart();

        if ($cart) {
            $cart->items()->where('id', $cartItemId)->delete();
        }
    }

    public function getCount(): int
    {
        return $this->getCart()?->items()->sum('quantity') ?? 0;
    }

    public function getTotal(): float
    {
        $total = $this->getCart()
            ?->items()
            ->sum(DB::raw('price * quantity'));

        return (float) ($total ?? 0.0);
    }
}