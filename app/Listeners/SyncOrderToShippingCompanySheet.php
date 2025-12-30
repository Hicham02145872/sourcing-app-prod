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
    
    // Retry 3 times
    public $tries = 3;
    // Backoff strategy (minutes)
    public $backoff = [1, 5, 15];

    /**
     * Get the middleware the job should pass through.
     * Rate limit to avoid hitting Google API limits (approx 60/min).
     */
    public function middleware()
    {
        // Allow 30 attempts per minute (every 2 seconds)
        return [(new \Illuminate\Queue\Middleware\ThrottlesExceptions(30, 60))->backoff(5)];
    }

    public function handle($event): void
    {
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
                // Log warning but don't fail job unless it's critical. 
                // Mostly this means configuration is missing.
                return;
            }

            Log::info("Syncing order #{$order->id} to shipping company sheet...");

            $service = new ShippingCompanySheetService($order->shippingCompany);
            // Ensuring headers might be expensive to do every time. 
            // In a perfect world, this is done once on setup. 
            // We'll leave it or move it. Let's keep it for robustness but cache it?
            // For now, let's trust the service optimizes or we just call it.
            $service->ensureHeaders();
            
            $dto = \App\DTOs\ShippingSheetRowDTO::fromOrder($order);
            $service->upsertRow($dto);

            // Update success status (reset error)
            $order->update([
                'sheet_synced_at' => now(),
                'sheet_sync_error' => null
            ]);

            Log::info("Order #{$order->id} synced to shipping company sheet successfully.");

        } catch (\Exception $e) {
            Log::error("Failed to sync order #{$order->id} to shipping company sheet: " . $e->getMessage());
            
            // Save error to DB
            $order->update([
                'sheet_sync_error' => substr($e->getMessage(), 0, 1000) // Truncate if too long
            ]);
            
            throw $e; // Re-throw to trigger retry/fail logic
        }
    }
}
