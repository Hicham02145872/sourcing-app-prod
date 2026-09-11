<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_fees', function (Blueprint $table) {
            if (!Schema::hasColumn('shipping_fees', 'air_unit')) {
                $table->string('air_unit', 10)->nullable()->after('unit');
            }
            if (!Schema::hasColumn('shipping_fees', 'sea_unit')) {
                $table->string('sea_unit', 10)->nullable()->after('air_unit');
            }
            if (!Schema::hasColumn('shipping_fees', 'train_unit')) {
                $table->string('train_unit', 10)->nullable()->after('sea_unit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shipping_fees', function (Blueprint $table) {
            $columnsToDrop = array_filter([
                Schema::hasColumn('shipping_fees', 'air_unit') ? 'air_unit' : null,
                Schema::hasColumn('shipping_fees', 'sea_unit') ? 'sea_unit' : null,
                Schema::hasColumn('shipping_fees', 'train_unit') ? 'train_unit' : null,
            ]);
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
