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
            $table->decimal('initial_estimated_product_cost', 10, 2)->nullable()->after('net_profit_or_loss')->comment('Coût produit initial (copié de quotation)');
            $table->decimal('initial_estimated_shipping_cost', 10, 2)->nullable()->after('initial_estimated_product_cost')->comment('Coût transport initial (copié de quotation)');
            $table->decimal('initial_estimated_other_costs', 10, 2)->nullable()->after('initial_estimated_shipping_cost')->comment('Autres coûts initiaux (copié de quotation)');
            $table->text('cost_adjustment_notes')->nullable()->after('initial_estimated_other_costs')->comment('Notes sur les ajustements de coûts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->dropColumn([
                'initial_estimated_product_cost',
                'initial_estimated_shipping_cost',
                'initial_estimated_other_costs',
                'cost_adjustment_notes',
            ]);
        });
    }
};
