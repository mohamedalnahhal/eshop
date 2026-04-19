<?php

namespace App\Services\Orders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderItemService
{
    public function add(Order $order, Product $product, int $quantity, ?int $overwritePrice): OrderItem
    {
        return DB::transaction(function () use ($order, $product, $quantity, $overwritePrice) {

            $unitPrice = $overwritePrice !== null
                ? $overwritePrice
                : $this->resolveCurrentPrice($product);

            $item = $order->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->translations->pluck('name', 'locale')->toArray(),
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'price_overwritten' => $overwritePrice ? true : false
            ]);

            $item->forceFill([
                'total' => $unitPrice * $quantity
            ])->save();

            return $item;
        });
    }

    public function update(OrderItem $item, Product $product, int $quantity, ?int $overwritePrice): OrderItem
    {
        return DB::transaction(function () use ($item, $product, $quantity, $overwritePrice) {

            $unitPrice = $overwritePrice !== null
                ? $overwritePrice
                : $this->resolveCurrentPrice($product);

            $item->update([
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'price_overwritten' => $overwritePrice ? true : false
            ]);

            $item->forceFill([
                'total' => $unitPrice * $quantity
            ]);

            return $item;
        });
    }

    public function delete(OrderItem $item): void
    {
        DB::transaction(function () use ($item) {
            $item->delete();
        });
    }

    private function resolveCurrentPrice(Product $product): int
    {
        // TODO: replace with product pricing service when implemented
        return $product->price;
    }
}