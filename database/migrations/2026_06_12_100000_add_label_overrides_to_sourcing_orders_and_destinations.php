<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->string('label_seller_name', 255)->nullable()->after('sheet_sync_error');
            $table->string('label_product_name', 255)->nullable()->after('label_seller_name');
        });

        Schema::table('sourcing_request_destinations', function (Blueprint $table) {
            $table->text('label_address')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->dropColumn(['label_seller_name', 'label_product_name']);
        });

        Schema::table('sourcing_request_destinations', function (Blueprint $table) {
            $table->dropColumn('label_address');
        });
    }
};
