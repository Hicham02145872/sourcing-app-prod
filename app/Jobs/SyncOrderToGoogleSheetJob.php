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

            $data = [
                'id' => $this->sourcingOrder->id,
                'created_at' => $this->sourcingOrder->created_at->toDateTimeString(),
                'status' => $this->sourcingOrder->status,
                'client_name' => $this->sourcingOrder->user->name,
                'client_email' => $this->sourcingOrder->user->email,
                'product_name' => $this->sourcingOrder->quotation->sourcingRequest->product_name ?? 'N/A',
                'quantity' => $this->sourcingOrder->quotation->quantity ?? 0,
                'total_amount' => $this->sourcingOrder->total_amount,
                'currency' => $this->sourcingOrder->currency ?? 'USD',
                'shipping_method' => $this->sourcingOrder->shipping_method ?? 'N/A',
                'tracking_number' => $this->sourcingOrder->tracking_number ?? '',
                'admin_assigned' => $this->sourcingOrder->assignedAdmin->name ?? 'Unassigned',
            ];

            $googleSheetService->appendRow($data, $this->sourcingOrder->id);
        } catch (\Exception $e) {
            Log::error('Failed to sync order to Google Sheet from job', [
                'order_id' => $this->sourcingOrder->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
