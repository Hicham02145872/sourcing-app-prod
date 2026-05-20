<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->string('shared_id', 7)->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->dropUnique(['shared_id']);
            $table->dropColumn('shared_id');
        });
    }
};
