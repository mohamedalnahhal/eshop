<?php

namespace App\Services\Orders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    public function __construct(
      private OrderItemService $itemService
    ) {}

    public function create(array $data, ?bool $draft = true): Order
    {
        return DB::transaction(function () use ($data, $draft) {
            $order = new Order;

            $order->fill($data);

            $order->forceFill([
                'status' => $draft? OrderStatus::DRAFT : OrderStatus::PENDING,
                'currency' => tenant()->settings?->currency ?? config('app.default_currency'),
                'currency_decimals' => tenant()->settings?->currency_decimals ?? config('app.default_currency_decimals'),
            ]);

            $order->save();

            return $order;
        });
    }

    public function update(Order $order, array $data): Order
    {
        $this->ensureEditable($order);

        $order->forceFill($data);

        return $order->refresh();
    }

    public function addItem(Order $order, Product $product, int $quantity, ?int $overwritePrice): OrderItem
    {
        $this->ensureEditable($order);
    
        return DB::transaction(function () use ($order, $product, $quantity, $overwritePrice) {
    
            $item = $this->itemService->add($order, $product, $quantity, $overwritePrice);
    
            $this->recalculate($order);
    
            return $item;
        });
    }

    public function updateItem(OrderItem $item, Product $product, int $quantity, ?int $overwritePrice): OrderItem
    {
        $order = $item->order;

        $this->ensureEditable($order);

        return DB::transaction(function () use ($item, $product, $quantity, $overwritePrice) {

            $item = $this->itemService->update($item, $product, $quantity, $overwritePrice);

            $this->recalculate($item->order);

            return $item;
        });
    }

    public function removeItem(OrderItem $item): void
    {
        $order = $item->order;

        $this->ensureEditable($order);

        DB::transaction(function () use ($item, $order) {
            $item = $this->itemService->delete($item, $order);
            $this->recalculate($order);
        });
    }

    public function recalculate(Order $order): void
    {
        $subtotal = $order->items()->sum('total');

        $total = $subtotal
            + $order->shipping_fees
            - $order->discount;

        $order->forceFill([
            'subtotal' => $subtotal,
            'total' => max($total, 0),
        ])->save();
    }

    public function changeStatus(Order $order, OrderStatus $status): Order
    {
        $this->validateTransition($order, $status);

        $order->update(['status' => $status]);

        return $order;
    }

    private function ensureEditable(Order $order): void
    {
        if ($order->status !== OrderStatus::DRAFT) {
            throw new Exception("Order is not editable (not in draft)");
        }
    
        if ($order->payments()->whereNot('status', 'failed')->exists()) {
            throw new Exception("Order is locked due to payment activity");
        }
    }

    private function validateTransition(Order $order, OrderStatus $newStatus): void
    {
        $map = [
            OrderStatus::DRAFT->value => [OrderStatus::PENDING],
            OrderStatus::PENDING->value => [OrderStatus::PROCESSING, OrderStatus::CANCELLED],
            OrderStatus::PROCESSING->value => [OrderStatus::SHIPPED],
            OrderStatus::SHIPPED->value => [OrderStatus::DELIVERED],
        ];

        if (!in_array($newStatus, $map[$order->status->value] ?? [])) {
            throw new Exception("Invalid status transition");
        }
    }
}