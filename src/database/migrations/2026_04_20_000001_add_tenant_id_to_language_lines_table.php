<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('language_lines', function (Blueprint $table) {
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('language_lines', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });
    }
};
