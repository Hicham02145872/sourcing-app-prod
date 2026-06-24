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
            $table->timestamp('negotiated_at')->nullable()->after('assigned_at');
            $table->timestamp('accepted_at')->nullable()->after('negotiated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sourcing_requests', function (Blueprint $table) {
            $table->dropColumn(['negotiated_at', 'accepted_at']);
        });
    }
};
