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
        Schema::table('sourcing_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_to_admin_id')->nullable()->after('status'); // Adjust 'status' to the appropriate column
            $table->foreign('assigned_to_admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sourcing_requests', function (Blueprint $table) {
            $table->dropForeign(['assigned_to_admin_id']);
            $table->dropColumn('assigned_to_admin_id');
        });
    }
};
