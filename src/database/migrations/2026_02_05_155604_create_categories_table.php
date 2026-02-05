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
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('category_id')->primary();
            $table->foreignUuid('tenant_id')
                ->constrained('tenants', 'tenant_id')
                ->onDelete('cascade');
            $table->string('name', 45);
            $table->string('type', 45)->nullable(); // مثال: 'electronics', 'clothing'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
