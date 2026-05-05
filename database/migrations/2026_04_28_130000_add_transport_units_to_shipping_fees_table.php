<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_fees', function (Blueprint $table) {
            $table->string('air_unit', 10)->nullable()->after('unit');
            $table->string('sea_unit', 10)->nullable()->after('air_unit');
            $table->string('train_unit', 10)->nullable()->after('sea_unit');
        });
    }

    public function down(): void
    {
        Schema::table('shipping_fees', function (Blueprint $table) {
            $table->dropColumn(['air_unit', 'sea_unit', 'train_unit']);
        });
    }
};
