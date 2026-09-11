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
        Schema::table('google_sheet_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('google_sheet_settings', 'synced_fields')) {
                $table->json('synced_fields')->nullable()->after('sheet_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('google_sheet_settings', function (Blueprint $table) {
            if (Schema::hasColumn('google_sheet_settings', 'synced_fields')) {
                $table->dropColumn('synced_fields');
            }
        });
    }
};
