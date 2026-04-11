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
        Schema::create('addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->uuidMorphs('addressable');
            $table->enum('type', ['shipping', 'billing', 'pickup']);
            $table->string('address_line_1', 255);
            $table->string('city', 100);
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 3);
            $table->decimal('lng', 11, 8)->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['addressable_type', 'addressable_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
