<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignUuid('category_id')
                  ->constrained('categories')
                  ->cascadeOnDelete();
            $table->string('locale', 8);
            $table->string('name', 100);

            $table->unique(['category_id', 'locale']);
            $table->index(['category_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_translations');
    }
};