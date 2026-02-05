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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->string('name', 45);
            $table->string('username', 45)->unique();
            $table->string('email')->unique();
            $table->text('password');
            $table->string('phone', 45)->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->integer('role')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
