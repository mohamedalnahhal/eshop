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
        Schema::create('media', function (Blueprint $table) {
            $table->uuid('media_id')->primary();
            $table->string('model_type');
            $table->uuid('model_id');
            $table->string('file_path', 255);
            $table->enum('file_type', ['image', 'video', 'pdf', 'archive']);
            $table->decimal('file_size', 12, 2);
            $table->timestamps();
            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
