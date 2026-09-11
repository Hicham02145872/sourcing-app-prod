<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sb_id_sequence')) {
            Schema::create('sb_id_sequence', function (Blueprint $table) {
                $table->unsignedInteger('last_value')->default(0);
            });

            DB::table('sb_id_sequence')->insert(['last_value' => 0]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sb_id_sequence');
    }
};
