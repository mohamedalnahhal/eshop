<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('stock_adjustments', function (Blueprint $table) {
        $table->string('type')->after('product_id'); // purchase, production, damaged
        $table->string('status')->default('issued')->change(); // issued, waiting, done
        $table->foreignId('supplier_id')->nullable()->after('type')->constrained('suppliers')->nullOnDelete();
        $table->integer('updated_value')->after('supplier_id');
        $table->dropColumn('amount');
    });
}

public function down(): void
{
    Schema::table('stock_adjustments', function (Blueprint $table) {
        $table->dropForeign(['supplier_id']);
        $table->dropColumn(['type', 'supplier_id', 'updated_value']);
        $table->integer('amount');
        $table->string('status')->default('waiting')->change();
    });
}
};