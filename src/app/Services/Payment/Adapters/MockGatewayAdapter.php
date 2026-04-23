<?php

namespace App\Services\Payment\Adapters;

use App\Contracts\PaymentGatewayContract;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\CheckoutToken;
use App\Services\Payment\PaymentResult;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MockGatewayAdapter implements PaymentGatewayContract
{
    public function createCheckoutSession(Order $order, CheckoutToken $token): string
    {
        return route('mock-psp.checkout.pay', ['token' => $token->token]);
    }

    public function handleCallback(Request $request): PaymentResult
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'status' => ['required', 'string'],
            'transaction_id' => ['required', 'string'],
        ]);

        return new PaymentResult(
            checkoutToken: $validated['token'],
            status: $validated['status'] ? PaymentStatus::COMPLETED->value : PaymentStatus::FAILED->value,
            transactionReference: $validated['transaction_id'],
            gatewayResponse: null
        );
    }
}