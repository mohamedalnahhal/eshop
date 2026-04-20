<?php

namespace App\Services\Orders;

use App\Models\Cart;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Models\Product;
use App\Services\Checkout\CheckoutException;
use Illuminate\Support\Facades\DB;

class CustomerOrderService
{
    public function create(array $data, bool $draft = true): Order
    {
        return DB::transaction(function () use ($data, $draft) {
            $order = new Order;
            $order->fill($data);
            $order->forceFill([
                'status' => $draft? OrderStatus::DRAFT : OrderStatus::PENDING,
                'currency' => tenant()->settings?->currency ?? config('app.default_currency'),
                'currency_decimals' => tenant()->settings?->currency_decimals ?? config('app.default_currency_decimals'),
            ])->save();

            return $order;
        });
    }

    /**
     * transfer all cart items onto a newly created PENDING order
     *
     * IMPORTANT:
     * - Locks product row to prvent price change while transfering
     * - Decrement stock atomically
     *
     * @return int order's subtotal
     * 
     * @throws CheckoutException
     */
    public function transferCartItems(Order $order, Cart $cart): int
    {
        $subtotal = 0;

        foreach ($cart->items as $cartItem) {
            $product = Product::with('translations')
                ->lockForUpdate()
                ->find($cartItem->product_id);

            if (! $product || $product->stock < $cartItem->quantity) {
                throw CheckoutException::outOfStock(
                    $product?->name ?? 'Unknown product',
                    $product?->stock ?? 0,
                );
            }

            $product->decrement('stock', $cartItem->quantity);

            $unitPrice = $product->price;
            $lineTotal = $unitPrice * $cartItem->quantity;
            $subtotal += $lineTotal;

            $order->items()->make([
                'product_id' => $product->id,
                'product_name' => $product->translations->pluck('name', 'locale')->toArray(),
                'quantity' => $cartItem->quantity,
                'unit_price' => $unitPrice,
                'price_overwritten' => false,
            ])->forceFill(['total' => $lineTotal])->save();
        }

        return $subtotal;
    }

    public function sealTotals(Order $order, int $subtotal, int $shippingFee)
    {
        $order->forceFill([
            'subtotal' => $subtotal,
            'shipping_fees' => $shippingFee,
            'discount' => $order->discount ?? 0,
            'total' => max($subtotal + $shippingFee - ($order->discount ?? 0), 0),
        ])->save();
    }
}