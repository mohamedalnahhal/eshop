<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'id'         => (string) Str::uuid(),
            'name'       => fake()->company(),
            'status'     => 'active',
            'created_at' => now(),
        ];
    }

  public function configure()
{
    return $this->afterCreating(function (Tenant $tenant) {
        $centralDomain = config('tenancy.central_domains')[0] ?? 'localhost';

        // 1. الدومين
        DB::table('domains')->insert([
            'domain'     => Str::slug($tenant->name) . '.' . $centralDomain,
            'tenant_id'  => $tenant->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. وسيلة الدفع (مهم جداً أن تكون هنا قبل الطلبات)
        DB::table('payment_methods')->updateOrInsert(
            ['payment_method' => 'credit_card'],
            ['provider' => 'Stripe', 'is_active' => true, 'updated_at' => now()]
        );

        // 3. الفئات والمنتجات
        $categories = Category::factory()->count(5)->create(['tenant_id' => $tenant->id]);
        $products   = Product::factory()->count(20)->create(['tenant_id' => $tenant->id]);
        $products->each(fn($p) => $p->categories()->attach($categories->random()->id));

        // 4. المدير والموقع
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        
        $location = \App\Models\Location::factory()->create([
            'tenant_id'       => $tenant->id,
            'name'            => 'فرع: ' . $tenant->name,
            'is_pickup_point' => true,
        ]);

        $address = \App\Models\Address::factory()->create([
            'addressable_id'   => $admin->id,
            'addressable_type' => User::class,
            'type'             => 'shipping',
        ]);

        DB::table('tenant_users')->insert([
            'id'         => (string) Str::uuid(),
            'user_id'    => $admin->id,   
            'tenant_id'  => $tenant->id, 
            'role'       => UserRole::TENANT_OWNER,  
            'created_at' => now(),
        ]);

        // 5. السلة والطلبات والمدفوعات
        $cart = \App\Models\Cart::factory()->create(['tenant_id' => $tenant->id, 'user_id' => $admin->id]);
        \App\Models\CartItem::factory()->count(3)->create([
            'cart_id'    => $cart->id,
            'product_id' => $products->random()->id,
        ]);

        \App\Models\Order::factory()->count(3)->create([
            'tenant_id'           => $tenant->id,
            'user_id'             => $admin->id,
            'shipping_address_id' => $address->id,
        ])->each(function ($order) use ($products, $tenant) {
            
            \App\Models\OrderItem::factory()->count(2)->create([
                'order_id'   => $order->id,
                'product_id' => $products->random()->id,
                'unit_price' => fake()->randomFloat(2, 10, 100),
            ]);
            
            // الآن الإدخال سينجح لأن 'credit_card' موجودة مسبقاً في الجدول الأب
            DB::table('payments')->insert([
                'id'                    => (string) Str::uuid(),
                'tenant_id'             => $tenant->id,
                'paymentable_id'        => $order->id,
                'paymentable_type'      => \App\Models\Order::class,
                'amount'                => $order->final_price ?? 100,
                'currency'              => 'SAR',
                'status'                => 'completed',
                'payment_method'        => 'credit_card', 
                'transaction_reference' => 'TXN-' . Str::upper(Str::random(10)),
                'created_at'            => now(),
            ]);
        });

        // 6. الاشتراكات والميديا والإعدادات
        $subId = DB::table('subscriptions')->pluck('id')->random(); 
        \App\Models\TenantSubscription::factory()->create([
            'tenant_id'       => $tenant->id,
            'subscription_id' => $subId 
        ]);

        \App\Models\Media::factory()->create([
            'mediable_id'   => $tenant->id,
            'mediable_type' => \App\Models\Tenant::class,
            'collection_name' => 'central_logs', // ميزه باسم مختلف لتعرفه
        ]);

        \App\Models\TenantMedia::factory()->create([
            'tenant_id'       => $tenant->id,
            'mediable_id'     => $tenant->id,
            'mediable_type'   => Tenant::class,
            'collection_name' => 'logo',
        ]);

        \App\Models\TenantSetting::factory()->count(4)->create([
            'tenant_id' => $tenant->id
        ]);
    });
}
}