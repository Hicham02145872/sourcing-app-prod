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
        Schema::table('shipping_fees', function (Blueprint $table) {
            $table->string('air_arrival_time')->nullable();
            $table->string('sea_arrival_time')->nullable();
            $table->string('train_arrival_time')->nullable();
        });

        Schema::table('shipping_fee_items', function (Blueprint $table) {
            $table->decimal('price_per_kg', 10, 2)->after('item_style');
            $table->dropColumn([
                'price_16_49',
                'price_50_99',
                'price_100_499',
                'price_plus_500',
                'estimation_days', // Moved to shipping_fees
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_fees', function (Blueprint $table) {
            $table->dropColumn(['air_arrival_time', 'sea_arrival_time', 'train_arrival_time']);
        });

        Schema::table('shipping_fee_items', function (Blueprint $table) {
            $table->dropColumn('price_per_kg');
            $table->decimal('price_16_49', 10, 2)->nullable();
            $table->decimal('price_50_99', 10, 2)->nullable();
            $table->decimal('price_100_499', 10, 2)->nullable();
            $table->decimal('price_plus_500', 10, 2)->nullable();
            $table->string('estimation_days')->nullable();
        });
    }
};
