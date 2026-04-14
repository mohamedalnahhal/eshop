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
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->jsonb('shipping_address'); // snapshot, no FK
            $table->jsonb('billing_address')->nullable(); // snapshot, no FK
            $table->decimal('total_price', 10, 2);   
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('final_price', 10, 2);    
            $table->string('currency', 3); // snapshot
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded']);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
