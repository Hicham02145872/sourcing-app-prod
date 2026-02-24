<?php

namespace App\Listeners;

use App\Events\ProofOfPaymentUploadedEvent;
use App\Events\SourcingOrderStatusChanged;
use App\Models\SourcingOrder;
use App\Services\SheetIntegrationFactory;
use App\Support\SheetSyncErrorHelper;
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

        $order->load(['shippingCompany', 'quotation.sourcingRequest.destinations', 'destinationShipments']);

        // Multi-destination: sync each destination to its assigned company's sheet
        if ($order->hasMultipleDestinations()) {
            $this->syncMultiDestinationOrder($order, $event);
            return;
        }

        // Single destination: must have global shipping company assigned
        if (! $order->shipping_company_id) {
            return;
        }

        $company = $order->shippingCompany;
        $service = $this->factory->getService($company);
        if (! $service) {
            Log::info("No sheet integration service found for company: {$company->name}");

            return;
        }

        $eventKey = ($event instanceof SourcingOrderStatusChanged) ? "status_{$order->status}" : 'payment';
        $lockKey = "sheet_sync_lock_{$order->id}_{$eventKey}";
        if (Cache::has($lockKey)) {
            Log::info("Aborting sync for Order #{$order->id} ({$eventKey}): Task already in progress or recently finished.");

            return;
        }
        Cache::put($lockKey, true, now()->addMinutes(2));

        try {
            Log::info("Syncing Order #{$order->id} to {$company->name} integration...");
            $success = $service->syncOrder($order, $company);

            if ($success) {
                Log::info("Order #{$order->id} synced successfully to {$company->name} sheet.");
                SourcingOrder::where('id', $order->id)->update([
                    'sheet_synced_at' => now(),
                    'sheet_sync_error' => null,
                ]);
            } else {
                throw new \Exception("Sync failed for Order #{$order->id}");
            }

        } catch (\Throwable $e) {
            Log::error("Error syncing Order #{$order->id} to sheet: ".$e->getMessage());
            $userMessage = SheetSyncErrorHelper::toUserMessage($e);
            SourcingOrder::where('id', $order->id)->update([
                'sheet_sync_error' => $userMessage,
            ]);
            throw $e;
        }
    }

    /**
     * When order has multiple destinations, group by assigned shipping company and sync
     * each destination only to that company's sheet.
     */
    protected function syncMultiDestinationOrder(SourcingOrder $order, $event): void
    {
        $eventKey = ($event instanceof SourcingOrderStatusChanged) ? "status_{$order->status}" : 'payment';
        $lockKey = "sheet_sync_lock_{$order->id}_{$eventKey}";
        if (Cache::has($lockKey)) {
            Log::info("Aborting multi-destination sync for Order #{$order->id} ({$eventKey}): Task already in progress or recently finished.");
            return;
        }
        Cache::put($lockKey, true, now()->addMinutes(2));

        $destinationsById = $order->quotation->sourcingRequest->destinations->keyBy('id');
        $byCompany = $order->destinationShipments
            ->filter(fn ($s) => ! empty($s->shipping_company_id))
            ->groupBy('shipping_company_id');

        if ($byCompany->isEmpty()) {
            Log::info("Order #{$order->id} has multiple destinations but none assigned to a shipping company; skipping sheet sync.");
            Cache::forget($lockKey);
            return;
        }

        $lastError = null;
        $anySuccess = false;

        foreach ($byCompany as $companyId => $shipments) {
            $company = \App\Models\ShippingCompany::find($companyId);
            if (! $company) {
                continue;
            }
            $service = $this->factory->getService($company);
            if (! $service) {
                Log::info("No sheet integration service found for company: {$company->name}");
                continue;
            }

            $destinationIds = $shipments->pluck('sourcing_request_destination_id')->filter()->values();
            $destinations = $destinationIds->map(fn ($id) => $destinationsById->get($id))->filter()->values();

            if ($destinations->isEmpty()) {
                continue;
            }

            try {
                Log::info("Syncing Order #{$order->id} destinations to {$company->name} (".$destinations->count()." rows)...");
                $success = $service->syncOrderDestinations($order, $company, $destinations);
                if ($success) {
                    $anySuccess = true;
                } else {
                    $lastError = "Sync failed for {$company->name}";
                }
            } catch (\Throwable $e) {
                Log::error("Error syncing Order #{$order->id} to {$company->name}: ".$e->getMessage());
                $lastError = $e->getMessage();
            }
        }

        Cache::forget($lockKey);

        if ($anySuccess) {
            SourcingOrder::where('id', $order->id)->update([
                'sheet_synced_at' => now(),
                'sheet_sync_error' => $lastError ? \App\Support\SheetSyncErrorHelper::toUserMessage(new \Exception($lastError)) : null,
            ]);
        }
        if ($lastError && ! $anySuccess) {
            throw new \Exception("Multi-destination sync failed for Order #{$order->id}: ".$lastError);
        }
    }
}
