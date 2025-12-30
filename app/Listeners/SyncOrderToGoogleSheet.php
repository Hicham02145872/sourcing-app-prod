<?php

namespace App\Listeners;

use App\Events\ProofOfPaymentUploadedEvent;
use App\Services\GoogleSheetService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SyncOrderToGoogleSheet implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var array
     */
    public $backoff = [60, 300, 900]; // 1min, 5min, 15min

    /**
     * The unique ID of the job (prevents duplicate processing)
     */
    public function uniqueId(): string
    {
        return 'sync-order-'.$this->event->sourcingOrder->id;
    }

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [new \Illuminate\Queue\Middleware\RateLimited('google-sheets')];
    }

    /**
     * Handle the event.
     */
    public function handle(ProofOfPaymentUploadedEvent $event): void
    {
        $order = $event->sourcingOrder;

        // Vérifier si déjà synchronisé (protection contre duplications)
        $cacheKey = "order_synced_to_sheet_{$order->id}";
        if (Cache::has($cacheKey)) {
            Log::info("Order {$order->id} already synced to Google Sheet. Skipping duplicate.");

            return;
        }

        Log::debug("SyncOrderToGoogleSheet listener handled for order #{$order->id}");

        try {
            Log::debug("Preparing data for order #{$order->id}");
            // 2. Préparer les données à envoyer
            $order->load(['user', 'quotation.sourcingRequest', 'assignedAdmin']); // S'assurer que les relations sont chargées

            $data = $order->toGoogleSheetArray();
            Log::debug("Data prepared for order #{$order->id}", ['data' => $data]);

            Log::debug("Initializing GoogleSheetService for order #{$order->id}");
            // 3. Initialiser le client Google Sheets et 4. Envoyer les données
            $googleSheetService = new GoogleSheetService;
            Log::debug("GoogleSheetService initialized for order #{$order->id}");

            Log::debug("Upserting row to Google Sheet for order #{$order->id}");
            $googleSheetService->upsertRow($data, $order->display_id, $order->id);
            Log::debug("Row upserted to Google Sheet for order #{$order->id}");

            // Marquer comme synchronisé (garder en cache pendant 24h)
            Cache::put($cacheKey, true, now()->addDay());

            Log::info("Sourcing Order {$order->id} data successfully synced to Google Sheet.", ['order_id' => $order->id]);

        } catch (\Exception $e) {
            Log::error("Failed to sync Sourcing Order {$order->id} to Google Sheet: ".$e->getMessage(), ['order_id' => $order->id, 'exception' => $e]);
            // Re-throw the exception to ensure the job fails and retries if it's a transient error
            throw $e;
        }
    }
}
