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
        if (! Schema::hasColumn('sourcing_orders', 'net_profit_or_loss')) {
            Schema::table('sourcing_orders', function (Blueprint $table) {
                $table->decimal('net_profit_or_loss', 10, 2)->nullable()->index()->after('rejection_loss_cost');
            });
        }
        // Removed change() to avoid data truncation on existing NULLs. Handled in Model.

        Schema::table('sourcing_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('sourcing_requests', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable()->after('assigned_to_admin_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->dropColumn(['net_profit_or_loss']);
        });

        Schema::table('sourcing_requests', function (Blueprint $table) {
            $table->dropColumn(['assigned_at']);
        });
    }
};
