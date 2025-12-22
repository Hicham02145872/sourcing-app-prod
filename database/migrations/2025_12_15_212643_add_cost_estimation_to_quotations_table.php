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
            $table->decimal('estimated_product_cost', 10, 2)->nullable()->after('delivery_cost_china')->comment('Coût estimé du produit');
            $table->decimal('estimated_shipping_cost', 10, 2)->nullable()->after('estimated_product_cost')->comment('Coût estimé de transport');
            $table->decimal('estimated_other_costs', 10, 2)->nullable()->after('estimated_shipping_cost')->comment('Autres coûts estimés');
            $table->decimal('estimated_net_profit', 10, 2)->nullable()->after('estimated_other_costs')->comment('Marge nette estimée (auto-calculée)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn([
                'estimated_product_cost',
                'estimated_shipping_cost',
                'estimated_other_costs',
                'estimated_net_profit',
            ]);
        });
    }
};
