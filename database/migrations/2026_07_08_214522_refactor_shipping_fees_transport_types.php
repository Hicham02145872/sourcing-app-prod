<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_fees', function (Blueprint $table) {
            $table->renameColumn('air_arrival_time', 'air_direct_arrival_time');
            $table->renameColumn('train_arrival_time', 'air_indirect_arrival_time');
            $table->renameColumn('air_unit', 'air_direct_unit');
            $table->renameColumn('train_unit', 'air_indirect_unit');
            
            $table->boolean('is_air_direct_visible')->default(true);
            $table->boolean('is_air_indirect_visible')->default(true);
            $table->boolean('is_sea_visible')->default(true);
        });

        DB::table('shipping_fee_items')->where('transport_type', 'air')->update(['transport_type' => 'air_direct']);
        DB::table('shipping_fee_items')->where('transport_type', 'train')->update(['transport_type' => 'air_indirect']);
    }

    public function down(): void
    {
        Schema::table('shipping_fees', function (Blueprint $table) {
            $table->renameColumn('air_direct_arrival_time', 'air_arrival_time');
            $table->renameColumn('air_indirect_arrival_time', 'train_arrival_time');
            $table->renameColumn('air_direct_unit', 'air_unit');
            $table->renameColumn('air_indirect_unit', 'train_unit');
            
            $table->dropColumn(['is_air_direct_visible', 'is_air_indirect_visible', 'is_sea_visible']);
        });

        DB::table('shipping_fee_items')->where('transport_type', 'air_direct')->update(['transport_type' => 'air']);
        DB::table('shipping_fee_items')->where('transport_type', 'air_indirect')->update(['transport_type' => 'train']);
    }
};
