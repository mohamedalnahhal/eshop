<?php

namespace App\Services\Shipping;

use App\Models\ShippingMethod;

final readonly class AvailableShippingMethod
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $description,
        public ?string $estimatedDelivery,
        public int $fee,
        public bool $isFree,
        public int $sortOrder,
    ) {}

    public static function fromModel(ShippingMethod $method, int $fee): self
    {
        return new self(
            id: $method->id,
            name: $method->name,
            description: $method->description,
            estimatedDelivery: $method->estimated_delivery,
            fee: $fee,
            isFree: $fee === 0,
            sortOrder: $method->sort_order,
        );
    }
}