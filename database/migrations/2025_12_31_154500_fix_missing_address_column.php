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
        Schema::table('sourcing_request_destinations', function (Blueprint $table) {
            if (!Schema::hasColumn('sourcing_request_destinations', 'address')) {
                $table->string('address')->after('quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sourcing_request_destinations', function (Blueprint $table) {
            $table->dropColumn('address');
        });
    }
};
