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
        Schema::table('refund_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('refund_requests', 'damaged_quantity')) {
                $table->integer('damaged_quantity')->nullable()->after('amount_requested');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            if (Schema::hasColumn('refund_requests', 'damaged_quantity')) {
                $table->dropColumn('damaged_quantity');
            }
        });
    }
};
