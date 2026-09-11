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
        Schema::table('shipping_companies', function (Blueprint $table) {
            if (!Schema::hasColumn('shipping_companies', 'lark_app_id')) {
                $table->string('lark_app_id')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('shipping_companies', 'lark_app_secret')) {
                $table->string('lark_app_secret')->nullable()->after('lark_app_id');
            }
            if (!Schema::hasColumn('shipping_companies', 'lark_base_token')) {
                $table->string('lark_base_token')->nullable()->after('lark_app_secret');
            }
            if (!Schema::hasColumn('shipping_companies', 'lark_table_id')) {
                $table->string('lark_table_id')->nullable()->after('lark_base_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_companies', function (Blueprint $table) {
            $columnsToDrop = array_filter([
                Schema::hasColumn('shipping_companies', 'lark_app_id') ? 'lark_app_id' : null,
                Schema::hasColumn('shipping_companies', 'lark_app_secret') ? 'lark_app_secret' : null,
                Schema::hasColumn('shipping_companies', 'lark_base_token') ? 'lark_base_token' : null,
                Schema::hasColumn('shipping_companies', 'lark_table_id') ? 'lark_table_id' : null,
            ]);
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
