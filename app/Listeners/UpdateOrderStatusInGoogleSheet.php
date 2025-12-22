<?php

namespace App\Listeners;

use App\Events\SourcingOrderStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateOrderStatusInGoogleSheet implements ShouldQueue
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
    public $backoff = [60, 300];

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
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SourcingOrderStatusChanged $event): void
    {
        $order = $event->sourcingOrder;

        // Optionally check if this order was ever synced to avoid unnecessary API calls
        // Use a cache key or check if a sync log exists for creation
        // For now, we will attempt update and the service handles "not found" gracefully

        try {
            $order->load(['user', 'quotation.sourcingRequest', 'assignedAdmin']);
            $googleSheetService = new \App\Services\GoogleSheetService;
            $googleSheetService->upsertRow($order->toGoogleSheetArray(), $order->id);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to update Order #{$order->id} in Google Sheet on status change: ".$e->getMessage());
            throw $e; // Retry
        }
    }
}
