<?php

namespace App\Services\Subscription;

use App\Enums\PaymentOwnerType;
use App\Models\CheckoutToken;
use App\Models\TenantSubscription;
use App\Services\Payment\PaymentData;
use App\Services\Payment\PaymentService;

class SubscriptionPaymentService
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    /**
     * Create a platform-level charge for a tenant's subscription fee.
     * The currency and decimals default to USD cents.
     */
    public function createCharge(
        TenantSubscription $subscription,
        string $paymentMethodId,
        string $currency = 'USD',
        int $currencyDecimals = 2,
    ): CheckoutToken {
        return $this->paymentService->initializePayment(
            PaymentOwnerType::PLATFORM,
            new PaymentData(
                tenantId: $subscription->tenant_id,
                paymentMethodId: $paymentMethodId,
                amount: $subscription->subscription->price,
                currency: $currency,
                currencyDecimals: $currencyDecimals,
            ),
            $subscription
        );
    }
}
