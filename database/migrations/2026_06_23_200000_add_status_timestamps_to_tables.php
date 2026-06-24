<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('sourcing_requests', 'status_timestamps')) {
            Schema::table('sourcing_requests', function (Blueprint $table) {
                $table->json('status_timestamps')->nullable()->after('status');
            });
        }

        if (!Schema::hasColumn('sourcing_orders', 'status_timestamps')) {
            Schema::table('sourcing_orders', function (Blueprint $table) {
                $table->json('status_timestamps')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        Schema::table('sourcing_requests', function (Blueprint $table) {
            $table->dropColumn('status_timestamps');
        });

        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->dropColumn('status_timestamps');
        });
    }
};
