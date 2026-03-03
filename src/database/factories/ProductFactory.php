<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
 // database/factories/ProductFactory.php
public function definition(): array
{
    return [
        'id'          => (string) \Illuminate\Support\Str::uuid(),
        'name'        => fake()->words(3, true),
        'description' => fake()->sentence(),
        'price'       => fake()->randomFloat(2, 10, 500), // السعر العادي للمنتج
        'stock'       => fake()->numberBetween(0, 100),
        'tenant_id'   => null,
        'created_at'  => now(),
        'updated_at'  => now(),
    ];
}
}
