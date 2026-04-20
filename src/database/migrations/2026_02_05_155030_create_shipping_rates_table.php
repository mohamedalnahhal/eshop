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
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('shipping_method_id')->constrained('shipping_methods')->onDelete('cascade');

            $table->enum('rate_type', ['flat_rate', 'free', 'price_based', 'weight_based'])->default('flat_rate');
            $table->unsignedBigInteger('fee')->default(0);

            /**
             * price_based: minimum cart subtotal (minor units)
             * weight_based: minimum total weight (grams)
             * null means no lower bound
             */
            $table->unsignedBigInteger('condition_min')->nullable();

            /**
             * price_based: maximum cart subtotal (minor units)
             * weight_based: maximum total weight (grams)
             * null means no lower bound
             */
            $table->unsignedBigInteger('condition_max')->nullable();

            /**
             * null means feature disabled
             */
            $table->unsignedBigInteger('free_above')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};