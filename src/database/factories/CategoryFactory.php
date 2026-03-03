<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
{
    return [
       // 'category' => (string) \Illuminate\Support\Str::uuid(),
        'name'        => fake()->randomElement(['ألبان', 'أجبان', 'زيوت', 'معلبات', 'حلويات']),
        'type'        => 'main',
        'tenant_id'   => null,
        'created_at'  => now(),
    ];
}
}
