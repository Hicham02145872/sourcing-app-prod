<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->foreignId('sourcing_request_id')->nullable()->after('quotation_id')->constrained('sourcing_requests')->cascadeOnDelete();
        });

        DB::table('sourcing_orders')
            ->join('quotations', 'quotations.id', '=', 'sourcing_orders.quotation_id')
            ->update([
                'sourcing_orders.sourcing_request_id' => DB::raw('quotations.sourcing_request_id'),
            ]);

    }

    public function down(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->dropForeign(['sourcing_request_id']);
            $table->dropColumn('sourcing_request_id');
        });
    }
};