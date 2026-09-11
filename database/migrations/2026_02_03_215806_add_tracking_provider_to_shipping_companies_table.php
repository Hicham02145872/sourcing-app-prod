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
        Schema::table('shipping_companies', function (Blueprint $table) {
            if (!Schema::hasColumn('shipping_companies', 'tracking_provider')) {
                $table->string('tracking_provider')->nullable()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_companies', function (Blueprint $table) {
            if (Schema::hasColumn('shipping_companies', 'tracking_provider')) {
                $table->dropColumn('tracking_provider');
            }
        });
    }
};
