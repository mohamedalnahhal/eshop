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
    Schema::create('locations', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
        $table->string('name', 100);
        $table->string('type', 20)->default('branch');
        $table->string('city')->nullable();
        $table->text('address_line')->nullable();
        $table->string('phone')->nullable();
        $table->decimal('lat', 10, 8)->nullable();
        $table->decimal('lng', 11, 8)->nullable();
        $table->boolean('is_pickup_point')->default(false); 
        $table->boolean('is_visible_to_customers')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
