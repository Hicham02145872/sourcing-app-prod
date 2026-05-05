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
            $table->timestamp('sheet_synced_at')->nullable()->after('shipping_company_id');
            $table->text('sheet_sync_error')->nullable()->after('sheet_synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->dropColumn(['sheet_synced_at', 'sheet_sync_error']);
        });
    }
};
