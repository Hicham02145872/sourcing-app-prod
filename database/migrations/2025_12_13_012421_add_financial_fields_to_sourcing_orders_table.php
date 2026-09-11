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
            if (!Schema::hasColumn('sourcing_orders', 'product_cost_price')) {
                $table->decimal('product_cost_price', 10, 2)->nullable()->after('total_amount');
            }
            if (!Schema::hasColumn('sourcing_orders', 'shipping_cost_real')) {
                $table->decimal('shipping_cost_real', 10, 2)->nullable()->after('product_cost_price');
            }
            if (!Schema::hasColumn('sourcing_orders', 'rejection_loss_cost')) {
                $table->decimal('rejection_loss_cost', 10, 2)->nullable()->after('shipping_cost_real');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $columnsToDrop = array_filter([
                Schema::hasColumn('sourcing_orders', 'product_cost_price') ? 'product_cost_price' : null,
                Schema::hasColumn('sourcing_orders', 'shipping_cost_real') ? 'shipping_cost_real' : null,
                Schema::hasColumn('sourcing_orders', 'rejection_loss_cost') ? 'rejection_loss_cost' : null,
            ]);
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
