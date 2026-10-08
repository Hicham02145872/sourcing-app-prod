<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sourcing_request_destinations', function (Blueprint $table) {
            $table->string('sourcing_location', 20)->nullable()->after('address')
                ->comment('Routing chosen for this destination: china (direct) or dubai (indirect)');
        });
    }

    public function down(): void
    {
        Schema::table('sourcing_request_destinations', function (Blueprint $table) {
            $table->dropColumn('sourcing_location');
        });
    }
};
