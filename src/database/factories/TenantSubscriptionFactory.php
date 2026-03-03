<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TenantSubscription>
 */
class TenantSubscriptionFactory extends Factory
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
        'tenant_id'       => null,
        'subscription_id' => null,
        'starts_at'       => now(),
        'ends_at'         => now()->addMonths(6),
        'status'          => 'active',
        'created_at'      => now(),
    ];
}
}
