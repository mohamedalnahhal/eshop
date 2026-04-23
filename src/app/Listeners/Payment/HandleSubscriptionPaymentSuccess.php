<?php

namespace App\Listeners\Payment;

use App\Enums\SubscriptionStatus;
use App\Events\Payment\PaymentSuccess;
use App\Models\TenantSubscription;

class HandleSubscriptionPaymentSuccess
{
    public function handle(PaymentSuccess $event): void
    {
        $payable = $event->payment->payable;

        if (!$payable instanceof TenantSubscription) return;

        $payable->update(['status' => SubscriptionStatus::ACTIVE]);
    }
}