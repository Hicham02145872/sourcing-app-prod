<?php

namespace App\Jobs;

use App\Models\SourcingOrder;
use App\Services\GoogleSheetService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncAllOrdersToGoogleSheet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600; // 10 minutes

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting full sync of orders to Google Sheet...');

        try {
            $googleSheetService = new GoogleSheetService;
            // Optional: Ensure headers exist before we start
            $googleSheetService->ensureHeaders();

            // Process in chunks to manage memory
            SourcingOrder::with(['user', 'quotation.sourcingRequest', 'assignedAdmin'])
                ->chunk(100, function ($orders) use ($googleSheetService) {

                    $batchData = [];
                    foreach ($orders as $order) {
                        $batchData[] = $order->toGoogleSheetArray();
                    }

                    if (! empty($batchData)) {
                        $stats = $googleSheetService->batchUpsertRows($batchData);
                        Log::info('Synced chunk of assignments: '.json_encode($stats));
                    }
                });

            Log::info('Completed full sync of orders to Google Sheet.');

        } catch (\Exception $e) {
            Log::error('Failed to sync all orders to Google Sheet: '.$e->getMessage());
            throw $e;
        }
    }
}
