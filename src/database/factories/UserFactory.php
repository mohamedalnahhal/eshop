<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\UserRole;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
{
    return [
        'id'    => (string) \Illuminate\Support\Str::uuid(),
        'name'       => fake()->name(),
        'username'   => fake()->unique()->userName(),
        'email'      => fake()->unique()->safeEmail(),
        'password'   => \Illuminate\Support\Facades\Hash::make('password'),
        'phone'      => fake()->phoneNumber(),
        'gender'     => fake()->randomElement(['male', 'female']),
        'role'       => UserRole::CUSTOMER, 
        'created_at' => now(),
    ];
}
}
