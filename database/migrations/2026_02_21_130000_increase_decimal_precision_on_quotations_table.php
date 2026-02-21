<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Increase decimal precision on monetary columns in the quotations table.
     * Previous decimal(8, 2) capped at 999,999.99 — insufficient for large amounts.
     */
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->nullable()->change();
            $table->decimal('unit_price', 15, 2)->nullable()->change();
            $table->decimal('commission_service', 15, 2)->nullable()->change();
            $table->decimal('unit_weight', 15, 2)->nullable()->change();
            $table->decimal('delivery_cost_china', 15, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->decimal('amount', 8, 2)->nullable()->change();
            $table->decimal('unit_price', 8, 2)->nullable()->change();
            $table->decimal('commission_service', 8, 2)->nullable()->change();
            $table->decimal('unit_weight', 8, 2)->nullable()->change();
            $table->decimal('delivery_cost_china', 8, 2)->nullable()->change();
        });
    }
};
