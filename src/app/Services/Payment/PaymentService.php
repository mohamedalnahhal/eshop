<?php

namespace App\Services\Payment;

use App\Contracts\Payable;
use App\Enums\PaymentOwnerType;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\CheckoutToken;
use App\Models\Payment;
use App\Events\Payment as PaymentEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class PaymentService
{
    public function initializePayment(PaymentOwnerType $owner, PaymentData $data, Payable $payable): CheckoutToken
    {
        $payment = $payable->payments()->create([
            'owner_id' => $owner,
            'tenant_id' => $data->tenantId,
            'payment_type' => PaymentType::CHARGE,
            'payment_method_id' => $data->paymentMethodId,
            'amount' => $data->amount,
            'currency' => $data->currency,
            'currency_decimals' => $data->currencyDecimals,
            'status' => PaymentStatus::PENDING, 
        ]);

        return $this->issueToken($payment);
    }

    /**
     * @throws Exception
     */
    public function finalizePayment(PaymentResult $dto)
    {
        $checkoutToken = CheckoutToken::where('token', $dto->checkoutToken)->lockForUpdate()->first();

        if (!$checkoutToken) {
            return null;
        }

        $pendingPayment = Payment::where('id', $checkoutToken->payment_id)
            ->where('status', PaymentStatus::PENDING)
            ->lockForUpdate()
            ->first();

        if (!$pendingPayment) {
            throw new Exception("No pending payment found.");
        }

        $pendingPayment->update([
            'status' => $dto->status,
            'transaction_reference' => $dto->transactionReference,
            'gateway_response' => $dto->gatewayResponse,
        ]);

        if ($dto->status === PaymentStatus::COMPLETED) event(new PaymentEvents\PaymentSuccess($pendingPayment));
        else event(new PaymentEvents\PaymentFaild($pendingPayment));

        $checkoutToken->delete();
    }

    public function refundPayment(Payment $payment, Payable $payable, int $amount): CheckoutToken
    {
        if ($payment->payment_type !== PaymentType::CHARGE) {
            throw new Exception("Can only refund charge payments");
        }
      
        if ($payment->status !== PaymentStatus::COMPLETED) {
            throw new Exception("Only completed payments can be refunded");
        }
      
        return DB::transaction(function () use ($payable, $payment, $amount) {
        
            $refundedSoFar = $payable->payments
                ->where('parent_payment_id', $payment->id)
                ->where('payment_type', PaymentType::REFUND)
                ->sum('amount');
        
            if ($refundedSoFar + $amount > $payment->amount) {
                throw new Exception("Refund exceeds original payment");
            }
          
            $payment = $payable->payments()->create([
                'owner_id' => $payment->owner_id,
                'tenant_id' => $payment->tenant_id,
                'payment_type' => PaymentType::REFUND,
                'parent_payment_id' => $payment->id,
                'payment_method_id' => $payment->payment_method,
                'amount' => $amount,
                'currency' => $payment->currency,
                'currency_decimals' => $payment->currency_decimals,
                'status' => PaymentStatus::PENDING,
            ]);

            return $this->issueToken($payment);
        });
        
    }

    private function issueToken(Payment $payment)
    {
        return $payment->payable()->checkoutTokens()->create([
            'payment_id' => $payment->id,
            'token' => Str::random(40),
            'expires_at' => now()->addMinutes(30),
        ]);
    }
}