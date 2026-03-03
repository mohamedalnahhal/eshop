<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id'             => (string) Str::uuid(), 
            'name'           => $this->faker->randomElement(['Basic Plan', 'Premium Plan', 'Enterprise Plan']),
            'price'          => $this->faker->randomFloat(2, 10, 100),
            'duration_days'  => 30,
            'max_products'   => $this->faker->numberBetween(50, 500),
            'features'       => json_encode(['support' => 'email', 'analytics' => true]),
            'created_at'     => now(),
            'updated_at'     => now(),
        ];
    }
}