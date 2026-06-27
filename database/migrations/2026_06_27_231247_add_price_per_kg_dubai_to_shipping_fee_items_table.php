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
            $table->decimal('price_per_kg_dubai', 10, 2)->nullable()->after('price_per_kg');
        });
    }

    public function down(): void
    {
        Schema::table('shipping_fee_items', function (Blueprint $table) {
            $table->dropColumn('price_per_kg_dubai');
        });
    }
};
