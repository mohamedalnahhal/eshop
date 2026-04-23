<?php

use App\Enums\OrderStatus;
use App\Events\Payment\PaymentFaild;
use App\Models\Order;

class HandleOrderPaymentFailed
{
    public function handle(PaymentFaild $event): void
    {
        $payable = $event->payment->payable;

        if (! $payable instanceof Order) return;

        $payable->update(['status' => OrderStatus::CANCELLED]);
    }
}