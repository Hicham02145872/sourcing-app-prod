<?php

namespace App\Console\Commands;

use App\Models\SourcingOrder;
use Illuminate\Console\Command;

class SyncSourcingOrderAssignments extends Command
{
    protected $signature = 'sourcing:sync-assignments';

    protected $description = 'Sync assigned admin from Sourcing Request to Sourcing Order and ensure consistency';

    public function handle()
    {
        // 1. Fill missing assignments on Orders from Requests
        $orders = SourcingOrder::whereNull('assigned_to_admin_id')->with('quotation.sourcingRequest')->get();
        $countFilled = 0;

        foreach ($orders as $order) {
            if ($order->quotation && $order->quotation->sourcingRequest && $order->quotation->sourcingRequest->assigned_to_admin_id) {
                $order->update(['assigned_to_admin_id' => $order->quotation->sourcingRequest->assigned_to_admin_id]);
                $countFilled++;
            }
        }

        $this->info("Filled $countFilled missing assignments.");

        // 2. Optional: Force sync even if not null (to ensure "responsable from request to order")
        // If the user wants strict sync, we should update Orders where assignment contradicts Request
        $allOrders = SourcingOrder::with('quotation.sourcingRequest')->get();
        $countSynced = 0;

        foreach ($allOrders as $order) {
            $requestAdminId = $order->quotation->sourcingRequest->assigned_to_admin_id ?? null;

            if ($requestAdminId && $order->assigned_to_admin_id !== $requestAdminId) {
                $order->update(['assigned_to_admin_id' => $requestAdminId]);
                $countSynced++;
            }
        }

        $this->info("Resynced $countSynced divergent assignments.");
    }
}
