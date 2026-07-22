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
            $table->string('china_to_dubai_currency')->nullable()->default('CNY')->after('dubai_to_destination_duration');
            $table->string('dubai_to_destination_currency')->nullable()->default('USD')->after('china_to_dubai_currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_fees', function (Blueprint $table) {
            $table->dropColumn(['china_to_dubai_currency', 'dubai_to_destination_currency']);
        });
    }
};
