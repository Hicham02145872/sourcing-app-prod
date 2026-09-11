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
            if (!Schema::hasColumn('shipping_fees', 'air_arrival_time')) {
                $table->string('air_arrival_time')->nullable();
            }
            if (!Schema::hasColumn('shipping_fees', 'sea_arrival_time')) {
                $table->string('sea_arrival_time')->nullable();
            }
            if (!Schema::hasColumn('shipping_fees', 'train_arrival_time')) {
                $table->string('train_arrival_time')->nullable();
            }
        });

        Schema::table('shipping_fee_items', function (Blueprint $table) {
            if (!Schema::hasColumn('shipping_fee_items', 'price_per_kg')) {
                $table->decimal('price_per_kg', 10, 2)->after('item_style');
            }
            $columnsToDrop = array_filter([
                Schema::hasColumn('shipping_fee_items', 'price_16_49') ? 'price_16_49' : null,
                Schema::hasColumn('shipping_fee_items', 'price_50_99') ? 'price_50_99' : null,
                Schema::hasColumn('shipping_fee_items', 'price_100_499') ? 'price_100_499' : null,
                Schema::hasColumn('shipping_fee_items', 'price_plus_500') ? 'price_plus_500' : null,
                Schema::hasColumn('shipping_fee_items', 'estimation_days') ? 'estimation_days' : null,
            ]);
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $dropShippingFees = array_filter([
            Schema::hasColumn('shipping_fees', 'air_arrival_time') ? 'air_arrival_time' : null,
            Schema::hasColumn('shipping_fees', 'sea_arrival_time') ? 'sea_arrival_time' : null,
            Schema::hasColumn('shipping_fees', 'train_arrival_time') ? 'train_arrival_time' : null,
        ]);
        if (! empty($dropShippingFees)) {
            Schema::table('shipping_fees', function (Blueprint $table) use ($dropShippingFees) {
                $table->dropColumn($dropShippingFees);
            });
        }

        Schema::table('shipping_fee_items', function (Blueprint $table) {
            if (Schema::hasColumn('shipping_fee_items', 'price_per_kg')) {
                $table->dropColumn('price_per_kg');
            }
            if (! Schema::hasColumn('shipping_fee_items', 'price_16_49')) {
                $table->decimal('price_16_49', 10, 2)->nullable();
            }
            if (! Schema::hasColumn('shipping_fee_items', 'price_50_99')) {
                $table->decimal('price_50_99', 10, 2)->nullable();
            }
            if (! Schema::hasColumn('shipping_fee_items', 'price_100_499')) {
                $table->decimal('price_100_499', 10, 2)->nullable();
            }
            if (! Schema::hasColumn('shipping_fee_items', 'price_plus_500')) {
                $table->decimal('price_plus_500', 10, 2)->nullable();
            }
            if (! Schema::hasColumn('shipping_fee_items', 'estimation_days')) {
                $table->string('estimation_days')->nullable();
            }
        });
    }
};
