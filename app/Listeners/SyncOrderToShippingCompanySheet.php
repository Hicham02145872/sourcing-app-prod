<?php

namespace App\Listeners;

use App\Events\SourcingOrderUpdated; // Assuming this event exists or we hook into 'saved'
use App\Models\SourcingOrder;
use App\Services\ShippingCompanySheetService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SyncOrderToShippingCompanySheet implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 3;
    public $backoff = [60, 300, 900];

    public function handle($event): void
    {
        // Handle different events if necessary. Assuming $event->sourcingOrder exists.
        $order = $event->sourcingOrder ?? null;

        if (!$order || !$order instanceof SourcingOrder) {
            return;
        }

        if (!$order->shipping_company_id) {
            return;
        }

        try {
            $order->load(['shippingCompany', 'user', 'quotation.sourcingRequest']);
            
            if (!$order->shippingCompany->google_sheet_id) {
                Log::warning("Order #{$order->id} assigned to shipping company without Sheet ID.");
                return;
            }

            Log::info("Syncing order #{$order->id} to shipping company sheet...");

            $service = new ShippingCompanySheetService($order->shippingCompany);
            $service->ensureHeaders(); // Ensure headers exist before syncing
            
            $data = $order->toShippingCompanySheetArray();
            $service->upsertRow($data, $order->display_id);

            Log::info("Order #{$order->id} synced to shipping company sheet successfully.");

        } catch (\Exception $e) {
            Log::error("Failed to sync order #{$order->id} to shipping company sheet: " . $e->getMessage());
            throw $e;
        }
    }
}
