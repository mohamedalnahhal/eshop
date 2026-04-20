<?php

namespace App\Services\Checkout;

use App\Models\Cart;
use App\Models\CheckoutToken;
use App\Models\Order;
use App\Models\ShippingMethod;
use App\Services\Orders\CustomerOrderService;
use App\Services\Shipping\ShippingCalculatorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    public function __construct(
        private readonly CustomerOrderService $customerOrderService,
        private readonly ShippingCalculatorService $shippingCalculator,
    ) {}

    /**
     * convert the active cart into a Pending order
     * 
     * @throws CheckoutException
     */
    public function process(Cart $cart, CheckoutData $data): CheckoutResult
    {
        return DB::transaction(function () use ($cart, $data): CheckoutResult {

            $cart->load(['items.product.translations', 'items.product.media']);
    
            if ($cart->items->isEmpty()) {
                throw CheckoutException::emptyCart();
            }

            // resolve shipping fees server-side

            $shippingMethod = ShippingMethod::with('rates')
                ->where('id', $data->shippingMethodId)
                ->where('is_active', true)
                ->first();

            if (! $shippingMethod) {
                throw CheckoutException::invalidShippingMethod();
            }

            $availableMethods = $this->shippingCalculator->getAvailableMethods($cart, $data->country);
            $resolvedMethod = $availableMethods->firstWhere('id', $data->shippingMethodId);

            if (!$resolvedMethod) {
                throw CheckoutException::invalidShippingMethod();
            }

            if ($resolvedMethod->fee !== $data->shippingFee) {
                throw CheckoutException::shippingFeeMismatch();
            }

            $shippingFee = $resolvedMethod->fee;

            $order = $this->buildOrder(
                cart: $cart,
                shippingMethod: $shippingMethod,
                shippingFee: $shippingFee,
                address: $this->buildAddressSnapshot($data),
                contact: [
                    'email' => $data->email,
                    'name' => $data->name,
                    'phone' => $data->phone,
                ],
                notes: $data->notes,
            );

            // issue a checkout token for the payment gateway
            $contactEmail = Auth::guard('customer')->user()?->email ?? $data->email;
            $token = $this->issueToken($order, $contactEmail, $order->total);

            return new CheckoutResult(
                order: $order,
                token: $token,
                lockedTotal: $order->total,
            );
        });
    }

    /**
     * convert the active cart into a Pending order in an express checkout flow
     *
     * The gateway supplies the customer address and the chosen shipping method Id
     * is provided from the payment gateway
     *
     * @throws CheckoutException
     */
    public function processExpress(Cart $cart, ExpressCheckoutData $data): CheckoutResult
    {
        return DB::transaction(function () use ($cart, $data): CheckoutResult {
 
            $cart->load(['items.product.translations', 'items.product.media']);
 
            if ($cart->items->isEmpty()) {
                throw CheckoutException::emptyCart();
            }
 
            $shippingMethod = ShippingMethod::with('rates')
                ->where('id', $data->shippingMethodId)
                ->where('is_active', true)
                ->first();
 
            if (!$shippingMethod) {
                throw CheckoutException::invalidShippingMethod();
            }

            $shippingFee = $this->shippingCalculator->resolveMethodFee(
                rates: $shippingMethod->rates, 
                subtotal: $cart->total(),
                weightGrams: $cart->weight(),
            );

            if ($shippingFee !== $data->shippingFee) {
                throw CheckoutException::shippingFeeMismatch();
            }

            $order = $this->buildOrder(
                cart: $cart,
                shippingMethod: $shippingMethod,
                shippingFee: $shippingFee,
                address: $this->buildAddressSnapshot($data),
                contact: [
                    'email' => $data->email,
                    'name' => $data->name,
                    'phone' => $data->phone,
                ],
                notes: $data->notes,
            );

            if ($order->total !== $data->total) {
                throw CheckoutException::totalMismatch(); 
            }

            $token = $this->issueToken($order, $data->email, $order->total);
 
            return new CheckoutResult(
                order: $order,
                token: $token,
                lockedTotal: $order->total,
            );
        });
    }
    
    private function buildOrder(
        Cart $cart,
        ShippingMethod $shippingMethod,
        int $shippingFee,
        array $address,
        array $contact,
        ?string $notes,
    ): Order {
        $customer = Auth::guard('customer')->user();
        $customerId = $customer?->id;
 
        $order = $this->customerOrderService->create(
            data: [
                'customer_id' => $customerId,
                'guest_email' => $customerId ? null : $contact['email'],
                'guest_name' => $customerId ? null : $contact['name'],
                'guest_phone' => $customerId ? null : $contact['phone'],
                'shipping_address' => $address,
                'billing_address' => null,
                'shipping_method_id' => $shippingMethod->id,
                'shipping_method_name' => $shippingMethod->name,
                'shipping_fees' => $shippingFee,
                'discount' => 0,
                'notes' => $notes,
            ],
            draft: false,
        );
 
        $subtotal = $this->customerOrderService->transferCartItems($order, $cart);
 
        $this->customerOrderService->sealTotals($order, $subtotal, $shippingFee);
 
        $cart->items()->delete();
        $cart->delete();
 
        return $order;
    }

    private function buildAddressSnapshot(CheckoutData|ExpressCheckoutData $data): array
    {
        return [
            'name' => $data->name,
            'line_1' => $data->addressLine1,
            'line_2' => $data->addressLine2,
            'city' => $data->city,
            'state' => $data->state,
            'postal_code' => $data->postalCode,
            'country' => $data->country,
            'phone' => $data->phone,
        ];
    }

    private function issueToken(Order $order, string $email, int $total): string
    {
        $raw = Str::random(40);

        CheckoutToken::create([
            'order_id' => $order->id,
            'token' => $raw,
            'locked_total' => $total,
            'customer_email' => $email,
            'expires_at' => now()->addMinutes(30),
        ]);

        return $raw;
    }
}