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
        Schema::create('addresses_tabl', function (Blueprint $table) {
            Schema::create('addresses', function (Blueprint $table) {
                $table->uuid('address_id')->primary();
                $table->foreignUuid('user_id')->constrained('users', 'user_id')->onDelete('cascade');
                $table->foreignUuid('tenant_id')->constrained('tenants', 'tenant_id')->onDelete('cascade');
                $table->enum('type', ['shipping', 'billing', 'home', 'work']);
                $table->string('address_line_1', 45);
                $table->string('city', 45);
                $table->string('state', 45)->nullable();
                $table->string('postal_code', 45)->nullable();
                $table->string('country', 45);
                $table->timestamps();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses_tabl');
    }
};
