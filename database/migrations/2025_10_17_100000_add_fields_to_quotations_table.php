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
        Schema::table('quotations', function (Blueprint $table) {
            $table->decimal('unit_price', 8, 2)->after('amount');
            $table->decimal('commission_service', 8, 2)->after('unit_price');
            $table->decimal('unit_weight', 8, 2)->after('commission_service');
            $table->decimal('delivery_cost_china', 8, 2)->after('unit_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['unit_price', 'commission_service', 'unit_weight', 'delivery_cost_china']);
        });
    }
};
