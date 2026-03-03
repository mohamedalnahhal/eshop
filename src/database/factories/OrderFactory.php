<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
// database/factories/OrderFactory.php
public function definition(): array
{
    $total = fake()->randomFloat(2, 50, 500);
    return [
        'id'          => (string) \Illuminate\Support\Str::uuid(),
        'total_price' => $total,
        'discount'    => 0,
        'final_price' => $total,
        'status'      => 'pending',
        'tenant_id'   => null,
        'user_id'     => null,
        'created_at'  => now(),
    ];
}
}
