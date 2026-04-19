<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenant_settings', function (Blueprint $table) {
            $table->json('supported_languages')
                  ->nullable()
                  ->after('language')
                  ->comment('e.g. ["ar","en","fr"]');

            $table->string('default_language', 10)
                  ->nullable()
                  ->after('supported_languages')
                  ->comment('The locale shown by default on the storefront');
        });
    }

    public function down(): void
    {
        Schema::table('tenant_settings', function (Blueprint $table) {
            $table->dropColumn(['supported_languages', 'default_language']);
        });
    }
};