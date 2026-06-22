<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->foreignId('sourcing_request_id')->nullable()->after('sourcing_order_id')->constrained('sourcing_requests')->cascadeOnDelete();
        });

        if (DB::getDriverName() === 'sqlite') {
            DB::table('refund_requests')->get()->each(function ($refund) {
                $order = DB::table('sourcing_orders')->where('id', $refund->sourcing_order_id)->first();
                if ($order) {
                    $quotation = DB::table('quotations')->where('id', $order->quotation_id)->first();
                    if ($quotation) {
                        DB::table('refund_requests')
                            ->where('id', $refund->id)
                            ->update(['sourcing_request_id' => $quotation->sourcing_request_id]);
                    }
                }
            });
        } else {
            DB::table('refund_requests')
                ->join('sourcing_orders', 'sourcing_orders.id', '=', 'refund_requests.sourcing_order_id')
                ->join('quotations', 'quotations.id', '=', 'sourcing_orders.quotation_id')
                ->update([
                    'refund_requests.sourcing_request_id' => DB::raw('quotations.sourcing_request_id'),
                ]);
        }

    }

    public function down(): void
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->dropForeign(['sourcing_request_id']);
            $table->dropColumn('sourcing_request_id');
        });
    }
};