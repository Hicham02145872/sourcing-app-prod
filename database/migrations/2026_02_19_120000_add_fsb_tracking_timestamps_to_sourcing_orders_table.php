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
            $table->timestamp('fsb_tracking_created_at')->nullable()->after('tracking_carrier');
            $table->timestamp('real_tracking_assigned_at')->nullable()->after('fsb_tracking_created_at');
            
            // Index pour optimiser les requêtes temporelles
            $table->index('fsb_tracking_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->dropIndex(['fsb_tracking_created_at']);
            $table->dropColumn(['fsb_tracking_created_at', 'real_tracking_assigned_at']);
        });
    }
};
