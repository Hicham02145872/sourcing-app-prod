<?php

namespace App\Listeners;

use App\Events\ProofOfPaymentUploadedEvent;
use App\Events\SourcingOrderStatusChanged;
use App\Models\SourcingOrder;
use App\Services\SheetIntegrationFactory;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SyncOrderToSheet implements ShouldBeUnique, ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The unique ID of the job (per event payload; Laravel derives from serialized listener/event).
     */
    public function uniqueId(): string
    {
        return 'sync-order-to-sheet';
    }

    public $tries = 3;

    public $backoff = [10, 60, 180];

    public function __construct(protected SheetIntegrationFactory $factory) {}

    public function handle($event): void
    {
        $order = null;
        if ($event instanceof SourcingOrderStatusChanged) {
            $order = $event->sourcingOrder;
        } elseif ($event instanceof ProofOfPaymentUploadedEvent) {
            $order = $event->sourcingOrder;
        }

        if (! $order || ! $order instanceof SourcingOrder) {
            return;
        }

        // Must have a shipping company assigned
        if (! $order->shipping_company_id) {
            return;
        }

        $order->load('shippingCompany');
        $company = $order->shippingCompany;

        $service = $this->factory->getService($company);
        if (! $service) {
            Log::info("No sheet integration service found for company: {$company->name}");

            return;
        }

        // Idempotency check to prevent redundant syncs
        $eventKey = ($event instanceof SourcingOrderStatusChanged) ? "status_{$order->status}" : 'payment';
        $lockKey = "sheet_sync_lock_{$order->id}_{$eventKey}";
        if (Cache::has($lockKey)) {
            Log::info("Aborting sync for Order #{$order->id} ({$eventKey}): Task already in progress or recently finished.");

            return;
        }
        Cache::put($lockKey, true, now()->addMinutes(2));

        try {
            Log::info("Syncing Order #{$order->id} to {$company->name} integration...");

            // If it's a status change, we might want to update instead of full sync
            // but for simplicity, most sheet services handle upsert/sync logically.
            // Let's use the service's syncOrder method which handles append/update.
            $success = $service->syncOrder($order, $company);

            if ($success) {
                Log::info("Order #{$order->id} synced successfully to {$company->name} sheet.");
            } else {
                throw new \Exception("Sync failed for Order #{$order->id}");
            }

        } catch (\Exception $e) {
            Log::error("Error syncing Order #{$order->id} to sheet: ".$e->getMessage());
            throw $e;
        }
    }
}
