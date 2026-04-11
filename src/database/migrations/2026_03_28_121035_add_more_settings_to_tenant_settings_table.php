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
        Schema::table('tenant_settings', function (Blueprint $table) {
            $table->text('favicon_url')->nullable()->after('language');
            $table->string('slogan')->nullable()->after('favicon_url');
            $table->string('currency', 3)->default('USD')->after('slogan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_settings', function (Blueprint $table) {
            $table->dropColumn([
                'favicon_url', 'slogan', 'currency',
            ]);
        });
    }
};
