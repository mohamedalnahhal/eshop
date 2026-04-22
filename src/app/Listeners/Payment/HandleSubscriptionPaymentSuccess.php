<?php

namespace App\Listeners\Payment;

use App\Enums\SubscriptionStatus;
use App\Events\Payment\PaymentSuccess;
use App\Models\Subscription;

class HandleSubscriptionPaymentSuccess
{
    public function handle(PaymentSuccess $event): void
    {
        $payable = $event->payment->payable;

        if (!$payable instanceof Subscription) return;

        $payable->update(['status' => SubscriptionStatus::ACTIVE]);
    }
}