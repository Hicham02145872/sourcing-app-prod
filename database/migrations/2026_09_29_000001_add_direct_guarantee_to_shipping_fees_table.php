<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_fees', function (Blueprint $table) {
            // Direct-route delivery guarantee shown as a badge in the routing
            // popup: 100 (default) or 50 — configured per country in admin.
            $table->unsignedTinyInteger('direct_guarantee')->default(100)->after('is_sea_visible');
        });
    }

    public function down(): void
    {
        Schema::table('shipping_fees', function (Blueprint $table) {
            $table->dropColumn('direct_guarantee');
        });
    }
};
