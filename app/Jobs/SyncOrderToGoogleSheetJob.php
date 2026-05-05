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

class SyncOrderToGoogleSheetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sourcingOrder;

    /**
     * Create a new job instance.
     */
    public function __construct(SourcingOrder $sourcingOrder)
    {
        $this->sourcingOrder = $sourcingOrder;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $googleSheetService = new GoogleSheetService;
            $this->sourcingOrder->load('user', 'quotation.sourcingRequest', 'assignedAdmin');

            $data = $this->sourcingOrder->toGoogleSheetArray();
            $googleSheetService->upsertRow($data, $this->sourcingOrder->display_id, $this->sourcingOrder->id);
        } catch (\Exception $e) {
            Log::error('Failed to sync order to Google Sheet from job', [
                'order_id' => $this->sourcingOrder->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
