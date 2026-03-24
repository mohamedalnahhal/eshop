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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('avg_rating', 3, 2)->default(0)->after('price');
            $table->unsignedInteger('reviews_count')->default(0);
            $table->unsignedBigInteger('rating_sum')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('avg_rating');
            $table->dropColumn('reviews_count');
            $table->dropColumn('rating_sum');
        });
    }
};
