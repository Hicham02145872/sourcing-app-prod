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
            if (Schema::hasColumn('sourcing_requests', 'product_url')) {
                $table->text('product_url')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sourcing_requests', function (Blueprint $table) {
            if (Schema::hasColumn('sourcing_requests', 'product_url')) {
                $table->string('product_url', 255)->nullable()->change();
            }
        });
    }
};
