<?php

namespace App\Services\Checkout;

final readonly class CheckoutData
{
    public function __construct(
        public string $email,
        public string $name,
        public ?string $phone,

        public string $addressLine1,
        public ?string $addressLine2,
        public string $city,
        public ?string $state,
        public ?string $postalCode,
        public string $country,

        public string $shippingMethodId,
        public int $shippingFee,

        public ?string $notes = null,
    ) {}
}