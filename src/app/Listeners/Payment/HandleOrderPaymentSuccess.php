<?php

namespace App\Listeners\Payment;

use App\Enums\OrderStatus;
use App\Events\Payment\PaymentSuccess;
use App\Models\Order;

class HandleOrderPaymentSuccess
{
    public function handle(PaymentSuccess $event): void
    {
        $payable = $event->payment->payable;

        if (!$payable instanceof Order) return;

        $payable->update(['status' => OrderStatus::PROCESSING]);
    }
}