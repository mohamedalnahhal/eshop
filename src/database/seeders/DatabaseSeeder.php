<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Tenant;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        
        $themeId = (string) Str::uuid();
        DB::table('themes')->updateOrInsert(
            ['name' => 'Default Theme'],
            [
                'id' => $themeId,
                'palette' => json_encode(['primary' => '#007bff', 'secondary' => '#6c757d']),
                'font' => 'Tajawal',
                'created_at' => now(),
            ]
        );

        $basicSubId = (string) Str::uuid();
        DB::table('subscriptions')->updateOrInsert(
            ['name' => 'Basic Plan'],
            [
                'id' => $basicSubId,
                'price' => 20.00,
                'duration_days' => 30,
                'max_products' => 100,
                'features' => json_encode(['support' => 'email']),
                'created_at' => now(),
            ]
        );

        DB::table('payment_methods')->updateOrInsert(
            ['payment_method' => 'stripe'],
            [
                'provider' => 'Stripe',
                'is_active' => true,
                'config' => json_encode(['currency' => 'USD']),
                'created_at' => now(),
            ]
        );

        Tenant::factory()->count(3)->create();

        $this->command->info('Database seeding completed successfully!');
    }
}
