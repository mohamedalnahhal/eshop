<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignUuid('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();
            $table->string('locale', 8);
            $table->string('name', 100);
            $table->text('description')->nullable();

            $table->unique(['product_id', 'locale']);
            $table->index(['product_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_translations');
    }
};