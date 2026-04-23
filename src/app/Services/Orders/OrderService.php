<?php

namespace App\Services\Orders;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * @param array{items?: array<int, array{product_id: string, quantity: int, overwrite_price_value: int|null}>, ...} $data
     */
    public function create(array $data, bool $draft = true): Order
    {
        return DB::transaction(function () use ($data, $draft) {
            $itemsData = $data['items'] ?? [];
            unset($data['items']);

            $order = new Order;

            $order->fill($data);

            $order->forceFill([
                'status' => $draft? OrderStatus::DRAFT : OrderStatus::PENDING,
                'currency' => tenant()->settings?->currency ?? config('app.default_currency'),
                'currency_decimals' => tenant()->settings?->currency_decimals ?? config('app.default_currency_decimals'),
            ]);

            $order->save();

            $products = $this->loadProducts($itemsData);

            $subtotal = 0;

            foreach ($itemsData as $row) {
                $product = $products->get($row['product_id']);

                if (!$product) {
                    continue;
                }

                $overridePrice = $row['overwrite_price_value'] ?? null;
                $unitPrice = $this->resolvePrice($product, $overridePrice);
                $quantity = (int) ($row['quantity'] ?? 1);

                $item = $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->translations->pluck('name', 'locale')->toArray(),
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'price_overwritten' => $overridePrice !== null
                ]);
    
                $item->forceFill([
                    'total' => $unitPrice * $quantity
                ])->save();
                
                $subtotal += $item->total;
            }

            $order->forceFill([
                'subtotal' => $subtotal,
                'shipping_fees' => $order->shipping_fees,
                'discount' => $order->discount ?? 0,
                'total' => max($subtotal + $order->shipping_fees - ($order->discount ?? 0), 0),
            ])->save();

            return $order;
        });
    }

    /**
     * @param array{items?: array<int, array{id?: string, product_id: string, quantity: int, overwrite_price_value: int|null}>, ...} $data
     */
    public function update(Order $order, array $data): Order
    {
        $this->ensureEditable($order);

        return DB::transaction(function () use ($order, $data): Order {
            $itemsData = $data['items'] ?? [];
            unset($data['items']);

            $order->fill($data)->save();

            $submittedIds = collect($itemsData)
                ->pluck('id')
                ->filter()
                ->values()
                ->all();

            $order->items()->whereNotIn('id', $submittedIds)->delete();

            $products = $this->loadProducts($itemsData);

            $subtotal = 0;

            foreach ($itemsData as $row) {
                $product = $products->get($row['product_id']);

                if (!$product) {
                    continue;
                }

                $quantity = (int) ($row['quantity'] ?? 1);
                $overridePrice = $row['overwrite_price_value'] ?? null;
                $unitPrice = $this->resolvePrice($product, $overridePrice);
                $item = null;
                if (!empty($row['id'])) {
                    $item = $order->items()->find($row['id']);
                }
                if ($item) {
                    $item->fill([
                        'product_id' => $product->id,
                        'product_name' => $product->translations->pluck('name', 'locale')->toArray(),
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total' => $unitPrice * $quantity,
                        'price_overwritten' => $overridePrice !== null
                    ]);
                } else {
                    $item = $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->translations->pluck('name', 'locale')->toArray(),
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'price_overwritten' => $overridePrice  !== null
                    ]);
                }
                $item->forceFill([
                    'total' => $unitPrice * $quantity
                ])->save();
                $subtotal += $item->total;
            }

            $order->forceFill([
                'subtotal' => $subtotal,
                'shipping_fees' => $order->shipping_fees,
                'discount' => $order->discount ?? 0,
                'total' => max($subtotal + $order->shipping_fees - ($order->discount ?? 0), 0),
            ])->save();

            return $order->refresh();
        });
    }

    public function changeStatus(Order $order, OrderStatus $status): Order
    {
        $this->validateTransition($order, $status);
        $order->forceFill(['status' => $status ])->save();
        return $order;
    }

    /**
     * @param  array<int, array{product_id: string, ...}> $itemsData
     * @return Collection<string, Product>
     */
    private function loadProducts(array $itemsData): Collection
    {
        $ids = collect($itemsData)->pluck('product_id')->filter()->unique()->values()->all();

        return Product::with('translations')
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');
    }

    /** @throws \RuntimeException */
    private function ensureEditable(Order $order)
    {
        if ($order->status !== OrderStatus::DRAFT) {
            throw new \RuntimeException("Order is not editable (not in draft)");
        }
    
        if ($order->payments()->whereNot('status', 'failed')->exists()) {
            throw new \RuntimeException("Order is locked due to payment activity");
        }
    }

    /** @throws \RuntimeException */
    private function validateTransition(Order $order, OrderStatus $newStatus)
    {
        $map = [
            OrderStatus::DRAFT->value => [OrderStatus::PENDING],
            OrderStatus::PENDING->value => [OrderStatus::PROCESSING, OrderStatus::CANCELLED],
            OrderStatus::PROCESSING->value => [OrderStatus::SHIPPED],
            OrderStatus::SHIPPED->value => [OrderStatus::DELIVERED],
        ];

        if (!in_array($newStatus, $map[$order->status->value] ?? [])) {
            throw new \RuntimeException("Invalid status transition");
        }
    }

    private function resolvePrice(Product $product, ?int $overridePrice): int
    {
        // TODO: replace with product pricing service when implemented
        return $overridePrice ?? $product->price;
    }
}