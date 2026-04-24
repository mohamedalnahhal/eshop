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
        $table->uuid('id')->primary();
        $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
        $table->string('shop_name')->nullable();
        $table->string('slogan')->nullable();
        $table->text('logo_url')->nullable();
        $table->text('favicon_url')->nullable();
        $table->string('contact_email')->nullable();
        $table->string('contact_phone')->nullable();
        $table->jsonb('supported_languages')->nullable();
        $table->string('default_language', 10)->nullable();
        $table->string('currency', 3)->default('USD');
        $table->unsignedSmallInteger('currency_decimals')->default(2);
        $table->foreignUuid('theme_id')->nullable()->constrained('themes')->onDelete('set null')->default(null);
        $table->boolean('guest_checkout_enabled')->default(true);
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
