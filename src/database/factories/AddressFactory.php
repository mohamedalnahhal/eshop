<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'id'               => fake()->uuid(),
            'type'             => fake()->randomElement(['shipping', 'billing', 'pickup']),
            'address_line_1'   => fake()->streetAddress(),
            'city'             => fake()->city(),
            'state'            => fake()->state(),
            'postal_code'      => fake()->postcode(),
            'country'          => 'SAU', // استخدمنا SAU لأن طول الحقل 3 أحرف فقط في المهاجرة
            'lat'              => fake()->latitude(),
            'lng'              => fake()->longitude(),
            'created_at'       => now(),
            'updated_at'       => now(),
        ];
    }
}

