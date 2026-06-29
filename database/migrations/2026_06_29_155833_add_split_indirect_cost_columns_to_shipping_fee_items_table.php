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
        Schema::table('shipping_fee_items', function (Blueprint $table) {
            $table->decimal('price_per_kg_china_to_dubai', 10, 2)->nullable()->after('price_per_kg_dubai');
            $table->decimal('price_per_kg_dubai_to_africa', 10, 2)->nullable()->after('price_per_kg_china_to_dubai');
        });
    }

    public function down(): void
    {
        Schema::table('shipping_fee_items', function (Blueprint $table) {
            $table->dropColumn(['price_per_kg_china_to_dubai', 'price_per_kg_dubai_to_africa']);
        });
    }
};
