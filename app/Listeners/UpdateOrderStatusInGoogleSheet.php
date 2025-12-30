<?php

namespace App\Listeners;

use App\Events\SourcingOrderStatusChanged;
use App\Services\GoogleSheetService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

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
        $order->load(['user', 'quotation.sourcingRequest', 'assignedAdmin', 'shippingCompany']);

        try {
            // Always sync to global sheet from this listener
            Log::info("Syncing order #{$order->id} to global Google Sheet.");

            $googleSheetService = new GoogleSheetService();
            $googleSheetService->upsertRow($order->toGoogleSheetArray(), $order->display_id, $order->id);

        } catch (\Exception $e) {
            Log::error("Failed to update Order #{$order->id} in Google Sheet on status change: ".$e->getMessage());
            throw $e; // Retry
        }
    }
}
