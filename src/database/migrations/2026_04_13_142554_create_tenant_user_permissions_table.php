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
        Schema::create('tenant_user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('tenant_user_id')->constrained('tenant_users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('permission');
            $table->boolean('granted');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_user_permissions');
    }
};
