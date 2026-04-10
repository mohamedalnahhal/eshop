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
    $tables = [
        'tenants', 'users', 'products', 'categories', 
        'orders', 'addresses', 'payments', 'subscriptions', 
        'tenant_subscriptions', 'locations'
    ];

    foreach ($tables as $tableName) {
        if (Schema::hasTable($tableName)) {
            Schema::table($tableName, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }
}

public function down(): void
{
    foreach (['tenants', 'users', 'products', 'categories', 'orders', 'addresses', 'payments', 'subscriptions', 'tenant_subscriptions', 'locations'] as $tableName) {
        if (Schema::hasTable($tableName)) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
}
};