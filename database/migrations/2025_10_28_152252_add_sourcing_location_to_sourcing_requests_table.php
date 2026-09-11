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
            if (!Schema::hasColumn('sourcing_requests', 'sourcing_location')) {
                $table->string('sourcing_location')->default('china');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sourcing_requests', function (Blueprint $table) {
            if (Schema::hasColumn('sourcing_requests', 'sourcing_location')) {
                $table->dropColumn('sourcing_location');
            }
        });
    }
};
