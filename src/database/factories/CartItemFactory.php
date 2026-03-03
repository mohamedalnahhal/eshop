<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItem>
 */
class CartItemFactory extends Factory
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
        'cart_id'      => null, 
        'product_id'   => null, 
        'quantity'     => fake()->numberBetween(1, 3),
        'updated_at'   => now(),
        'created_at'   => now(),
    ];
}
}

