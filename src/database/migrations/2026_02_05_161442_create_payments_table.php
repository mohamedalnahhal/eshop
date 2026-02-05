<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('payment_id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants', 'tenant_id')->onDelete('cascade');
            $table->foreignUuid('order_id')->nullable()->constrained('orders', 'order_id');
            $table->foreignUuid('subscription_payment_id')->nullable()->constrained('subscriptions', 'subscription_id');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('USD');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded']);
            $table->string('payment_method', 45);
            $table->foreign('payment_method')->references('payment_method')->on('payment_methods');
            $table->json('gateway_response')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
