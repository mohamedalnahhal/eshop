<?php

namespace App\Services\Payment;

final readonly class PaymentData
{
    public function __construct(
        public string $tenantId,
        public string $paymentMethodId,
        public int $amount,
        public string $currency,
        public int $currencyDecimals,
    ) {}
}