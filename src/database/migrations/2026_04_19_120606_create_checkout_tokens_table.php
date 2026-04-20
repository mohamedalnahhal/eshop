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
        Schema::create('checkout_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignUuid('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('token', 60)->unique();
            $table->unsignedBigInteger('locked_total'); // snapshot
            $table->string('customer_email', 255)->nullable(); // snapshot
            $table->timestamp('expires_at');
            $table->boolean('used')->default(false); // prevents replay attacks
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_tokens');
    }
};