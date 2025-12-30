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
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->foreignId('shipping_company_id')->nullable()->after('assigned_to_admin_id')->constrained('shipping_companies')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_company_id']);
            $table->dropColumn('shipping_company_id');
        });
    }
};
