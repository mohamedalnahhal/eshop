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
        Schema::create('tenant_settings', function (Blueprint $table) {
            $table->uuid('tenant_settings_id')->primary();
            $table->foreignUuid('tenant_id')
                ->constrained('tenants', 'tenant_id')
                ->onDelete('cascade');

            $table->string('language', 45)->default('ar'); 
            $table->string('logo_url', 255)->nullable(); 
            $table->foreignUuid('theme_id')
                ->constrained('themes', 'theme_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_settings');
    }
};
