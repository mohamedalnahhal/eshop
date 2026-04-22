<?php

namespace App\Services\Orders;

use App\Enums\PaymentOwnerType;
use App\Models\CheckoutToken;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Payment\PaymentData;
use App\Services\Payment\PaymentService;
use Exception;

class OrderPaymentService
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    public function createCharge(Order $order, int $amount, string $paymentMethodId): CheckoutToken
    {
        $this->ensurePayable($order);

        $paid = $this->getNetPaid($order);
        
        if ($paid + $amount > $order->total) {
            throw new Exception("Overpayment not allowed");
        }

        return $this->paymentService->initializePayment(
            PaymentOwnerType::TENANT,
            new PaymentData(
                tenantId: tenant('id'),
                paymentMethodId: $paymentMethodId,
                amount: $amount,
                currency: $order->currency,
                currencyDecimals: $order->currency_decimals
            ),
            $order
        );
    }

    public function refund(Order $order, Payment $payment, int $amount): CheckoutToken
    {
        throw new Exception("Order refund is not implemented yet.");
    }

    private function ensurePayable(Order $order): void
    {
        if (!in_array($order->status->value, ['pending', 'processing'])) {
            throw new Exception("Order is not payable");
        }
    }

    public function getNetPaid(Order $order): int
    {
        return $order->payments()
            ->where('status', 'completed')
            ->get()
            ->sum(function ($p) {
                return $p->payment_type === 'charge'
                    ? $p->amount
                    : -$p->amount;
            });
    }
}