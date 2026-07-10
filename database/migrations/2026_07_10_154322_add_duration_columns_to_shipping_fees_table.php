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
            $table->string('china_to_dubai_duration')->nullable()->after('air_indirect_arrival_time');
            $table->string('dubai_to_destination_duration')->nullable()->after('china_to_dubai_duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_fees', function (Blueprint $table) {
            $table->dropColumn(['china_to_dubai_duration', 'dubai_to_destination_duration']);
        });
    }
};
