<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Services\Checkout\CheckoutException;
use App\Services\Checkout\CheckoutService;
use App\Services\Checkout\ExpressCheckoutData;
use App\Services\Shipping\ShippingCalculatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpressCheckoutController extends Controller
{
    public function __construct(
        private readonly ShippingCalculatorService $shippingCalculator,
        private readonly CheckoutService $checkoutService,
    ) {}

    /**
     * POST /checkout/express/shipping-options
     *
     * Called by the gateway (Apple Pay / PayPal) before payment is collected.
     * Returns available shipping methods + fees for the given cart and country.
     *
     * Request body:
     *   { cart_id: string, country: string (ISO-3166-1 alpha-2) }
     *
     * Response 200:
     *   [{ id, name, description, estimatedDelivery, fee, isFree, sortOrder }, ...]
     *
     * Response 422:
     *   { message: string }
     */
    public function shippingOptions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cart_id' => ['required', 'string'],
            'country' => ['required', 'string', 'size:2'],
        ]);

        $cart = Cart::with(['items.product'])
            ->where('id', $validated['cart_id'])
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart not found or is empty.'], 422);
        }

        $subtotal = $cart->total();
        $weightGrams = (int) $cart->items->sum(
            fn ($item) => ($item->product?->weight_grams ?? 0) * $item->quantity
        );

        $methods = $this->shippingCalculator->getAvailableMethodsForValues(
            countryCode: strtoupper($validated['country']),
            subtotal: $subtotal,
            weightGrams: $weightGrams,
        );

        return response()->json($methods->values());
    }

    /**
     * POST /checkout/express/confirm
     *
     * Called by the gateway after the customer has authorised payment.
     * Creates a PENDING order from the cart, decrements stock, and returns
     * a checkout token for the payment processing step.
     *
     * Request body:
     *   {
     *     cart_id:            string,
     *     shipping_method_id: string,
     *     email:              string,
     *     name:               string,
     *     phone?:             string,
     *     address_line_1:     string,
     *     address_line_2?:    string,
     *     city:               string,
     *     state?:             string,
     *     postal_code?:       string,
     *     country:            string (ISO-3166-1 alpha-2),
     *     notes?:             string,
     *   }
     *
     * Response 200:
     *   { token: string }
     *
     * Response 422:
     *   { message: string }
     */
    public function confirm(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cart_id' => ['required', 'string'],
            'shipping_method_id' => ['required', 'string'],
            'shipping_fee' => ['required', 'integer', 'min:0'],
            'total' => ['required', 'integer', 'min:0'],
            'email' => ['required', 'email', 'max:255'],
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:32'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['required', 'string', 'size:2'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $cart = Cart::with(['items.product.translations', 'items.product.media'])
            ->where('id', $validated['cart_id'])
            ->where(function ($query) {
              $query->where('session_token', session()->getId())
                    ->orWhere('customer_id', Auth::guard('customer')->id());
            })
            ->first();

        if (! $cart) {
            return response()->json(['message' => 'Cart not found.'], 422);
        }

        $data = new ExpressCheckoutData(
            email: $validated['email'],
            name: $validated['name'],
            phone: $validated['phone'] ?? null,
            addressLine1: $validated['address_line_1'],
            addressLine2: $validated['address_line_2'] ?? null,
            city: $validated['city'],
            state: $validated['state'] ?? null,
            postalCode: $validated['postal_code'] ?? null,
            country: strtoupper($validated['country']),
            shippingMethodId: $validated['shipping_method_id'],
            shippingFee: $validated['shipping_fee'],
            total: $validated['total'],
            notes: $validated['notes'] ?? null,
        );

        try {
            $result = $this->checkoutService->processExpress($cart, $data);
        } catch (CheckoutException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['token' => $result->token]);
    }
}