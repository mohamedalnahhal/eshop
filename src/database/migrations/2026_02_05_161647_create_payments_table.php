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
            $table->uuid('id')->primary();
            $table->enum('owner_type', ['tenant', 'platform']);
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->uuidMorphs('paymentable');
            $table->string('payment_method', 50);
            $table->foreign('payment_method')->references('payment_method')->on('payment_methods');
            $table->foreignUuid('parent_payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->enum('payment_type', ['charge', 'refund']);
            $table->unsignedBigInteger('amount');
            $table->string('currency', 3); // snapshot
            $table->unsignedTinyInteger('currency_decimals'); // snapshot
            $table->enum('status', ['pending', 'completed', 'failed']);
            $table->string('transaction_reference', 255);
            $table->jsonb('gateway_response')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->softDeletes();
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
