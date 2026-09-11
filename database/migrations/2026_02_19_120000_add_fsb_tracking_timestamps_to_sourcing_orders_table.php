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
            if (!Schema::hasColumn('sourcing_orders', 'fsb_tracking_created_at')) {
                $table->timestamp('fsb_tracking_created_at')->nullable()->after('tracking_carrier');
            }
            if (!Schema::hasColumn('sourcing_orders', 'real_tracking_assigned_at')) {
                $table->timestamp('real_tracking_assigned_at')->nullable()->after('fsb_tracking_created_at');
            }
            if (!Schema::hasIndex('sourcing_orders', 'sourcing_orders_fsb_tracking_created_at_index')) {
                $table->index('fsb_tracking_created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            if (Schema::hasColumn('sourcing_orders', 'fsb_tracking_created_at')) {
                $table->dropIndex(['fsb_tracking_created_at']);
            }
            $columnsToDrop = array_filter([
                Schema::hasColumn('sourcing_orders', 'fsb_tracking_created_at') ? 'fsb_tracking_created_at' : null,
                Schema::hasColumn('sourcing_orders', 'real_tracking_assigned_at') ? 'real_tracking_assigned_at' : null,
            ]);
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
