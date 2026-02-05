<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
    $table->uuid('order_id')->primary();
    $table->decimal('total_price', 10, 2);   
    $table->decimal('discount', 10, 2)->default(0.00);
    $table->decimal('final_price', 10, 2);    
    $table->string('status', 45); 
    $table->foreignUuid('user_id')->constrained('users', 'user_id');
    $table->foreignUuid('tenant_id')->constrained('tenants', 'tenant_id')->onDelete('cascade');
    $table->foreignUuid('shipping_address_id')->constrained('addresses', 'address_id');
    $table->timestamp('created_at')->useCurrent();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_tabl');
    }
};
