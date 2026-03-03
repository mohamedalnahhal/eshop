<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
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
        'order_id'      => null, 
        'product_id'    => null, // سيُمرر
        'quantity'      => fake()->numberBetween(1, 5),
        'unit_price'    => fake()->randomFloat(2, 10, 100),
        'created_at'    => now(),
    ];
}
}
