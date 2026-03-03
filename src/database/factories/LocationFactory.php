<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
  public function definition(): array
{
    return [
        'id'      => (string) \Illuminate\Support\Str::uuid(),
        'tenant_id'        => null,
        'name'             => fake()->city() . ' Branch',
        'is_pickup_point'  => fake()->boolean(),
        'created_at'       => now(),
    ];
}
}
