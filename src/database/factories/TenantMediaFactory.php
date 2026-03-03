<?php

namespace Database\Factories;

use App\Models\TenantMedia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TenantMediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = TenantMedia::class;
    
  public function definition(): array
    {
        return [
            'id'              => fake()->uuid(),
            'tenant_id'       => null, // سيتم تمريره
            'mediable_id'     => fake()->uuid(),
            'mediable_type'   => 'App\Models\Tenant',
            'collection_name' => 'logo',
            'file_path'       => 'uploads/tenants/logos/' . fake()->uuid() . '.png',
            'file_type'       => 'image',
            'file_size'       => fake()->randomFloat(2, 10, 5000),
            'created_at'      => now(),
            'updated_at'      => now(),
        ];
    }
}
