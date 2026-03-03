<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'id' => (string) \Illuminate\Support\Str::uuid(),
        'tenant_id' => null,
        'paymentable_id' => null,
        'paymentable_type' => 'App\Models\Order',
        'amount' => fake()->randomFloat(2, 10, 500),
        'currency' => 'USD',
        'status' => 'success',
        'payment_method' => 'stripe',
        'transaction_reference' => 'txn_' . Str::random(10),
        'created_at' => now(),
    ];
}
}
