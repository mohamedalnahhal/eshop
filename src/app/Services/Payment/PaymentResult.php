<?php

namespace App\Services\Payment;

class PaymentResult
{
    public function __construct(
        public readonly string $checkoutToken,
        public readonly string $status, // 'completed' or 'failed'
        public readonly string $transactionReference,
        public readonly ?array $gatewayResponse
    ) {}
}