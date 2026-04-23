<?php

namespace App\Services\Payment\Adapters;

use App\Contracts\PaymentGatewayContract;
use App\Models\Order;
use App\Models\CheckoutToken;
use App\Services\Payment\PaymentResult;
use Illuminate\Http\Request;
use Exception;
// use Stripe\StripeClient;

class StripeGatewayAdapter implements PaymentGatewayContract
{
    public function __construct(
        // private readonly StripeClient $stripe
    ) {
      throw new Exception("Stripe adapter is not yet fully implemented.");
    }

    public function createCheckoutSession(Order $order, CheckoutToken $token): string
    {
        throw new Exception("Stripe adapter is not yet fully implemented.");

        // Stripe's API implemenation example

        // $session = $this->stripe->checkout->sessions->create([
        //     'payment_method_types' => ['card'],
        //     'line_items' => [[
        //         'price_data' => [
        //             'currency' => 'usd',
        //             'product_data' => ['name' => "Order #{$order->id}"],
        //             'unit_amount' => $token->locked_total,
        //         ],
        //         'quantity' => 1,
        //     ]],
        //     'mode' => 'payment',
        //     'customer_email' => $token->customer_email,
        //     'success_url' => route('checkout.success'),
        //     'cancel_url' => route('checkout.cancel'),
        //     'client_reference_id' => $token->token,
        // ]);

        // return $session->url; // checkout.stripe.com/...
    }

    public function handleCallback(Request $request): PaymentResult
    {
        throw new Exception('Stripe adapter is not yet fully implemented.');

        // Stripe webhook handling implemenation example

        // $payload = $request->getContent();
        // $sigHeader = $request->header('Stripe-Signature');

        // try {
        //     $event = Webhook::constructEvent(
        //         $payload, 
        //         $sigHeader, 
        //         $this->webhookSecret
        //     );
        // } catch (\UnexpectedValueException | \Stripe\Exception\SignatureVerificationException $e) {
        //     throw new Exception("Invalid Stripe Webhook Signature");
        // }

        // if ($event->type !== 'checkout.session.completed') {
        //     return null;
        // }

        // $session = $event->data->object;

        // return new PaymentResult(
        //     checkoutToken: $session->client_reference_id,
        //     status: PaymentStatus::COMPLETED->value,
        //     transactionReference: $session->payment_intent,
        //     gatewayResponse: null
        // );
    }
}