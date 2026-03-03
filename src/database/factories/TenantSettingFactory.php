<?php

namespace Database\Factories;

use App\Models\TenantSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenantSettingFactory extends Factory
{
    protected $model = TenantSetting::class;

    public function definition(): array
    {
        return [
            'id'        => fake()->uuid(),
            'tenant_id' => null, 
            'language'  => fake()->randomElement(['ar', 'en']),
            'theme_id'  => \DB::table('themes')->pluck('id')->random() ?? fake()->uuid(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
