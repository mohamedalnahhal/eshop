<?php

namespace App\Services\Payment;

use App\Models\PaymentMethod;
use App\Contracts\PaymentGatewayContract;
use App\Enums\PaymentProvider;
use InvalidArgumentException;

class PaymentGatewayFactory
{
    public function make(PaymentMethod $paymentMethod): PaymentGatewayContract
    {
        return match ($paymentMethod->provider) {
            
            PaymentProvider::MOCK => new Adapters\MockGatewayAdapter(),
            PaymentProvider::MOCK_EXPRESS => new Adapters\MockExpressGatewayAdapter(),
            
            // PaymentProvider::STRIPE => new Adapters\StripeGatewayAdapter(
            //   secretKey: $paymentMethod->config['stripe_secret_key']
            // ),
            
            // PaymentProvider::PAYPAL => new Adapters\PayPalGatewayAdapter(
            //     clientId: $paymentMethod->config['client_id'],
            //     secret: $paymentMethod->config['secret']
            // ),

            default => throw new InvalidArgumentException("Unsupported payment provider: {$paymentMethod->provider}"),
        };
    }
}