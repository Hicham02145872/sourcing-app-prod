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
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->decimal('product_cost_price', 10, 2)->nullable()->after('total_amount');
            $table->decimal('shipping_cost_real', 10, 2)->nullable()->after('product_cost_price');
            $table->decimal('rejection_loss_cost', 10, 2)->nullable()->after('shipping_cost_real');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->dropColumn(['product_cost_price', 'shipping_cost_real', 'rejection_loss_cost']);
        });
    }
};
