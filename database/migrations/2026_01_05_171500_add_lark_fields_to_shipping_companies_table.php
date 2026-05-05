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
            $table->string('lark_app_id')->nullable()->after('is_active');
            $table->string('lark_app_secret')->nullable()->after('lark_app_id');
            $table->string('lark_base_token')->nullable()->after('lark_app_secret');
            $table->string('lark_table_id')->nullable()->after('lark_base_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_companies', function (Blueprint $table) {
            $table->dropColumn(['lark_app_id', 'lark_app_secret', 'lark_base_token', 'lark_table_id']);
        });
    }
};
