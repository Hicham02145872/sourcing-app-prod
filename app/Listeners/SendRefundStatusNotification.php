<?php

namespace App\Listeners;

use App\Events\RefundRequestUpdated;
use App\Notifications\RefundStatusUpdated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendRefundStatusNotification
{
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
    public function handle(RefundRequestUpdated $event): void
    {
        $refundRequest = $event->refundRequest;
        $user = $refundRequest->user;

        $cacheKey = 'refund_status_notified_'.$refundRequest->id.'_'.$refundRequest->status;

        // Prevent duplicate notifications within 10 seconds
        if (! Cache::add($cacheKey, true, 10)) {
            Log::info('Refund notification skipped (duplicate detected)', [
                'refund_request_id' => $refundRequest->id,
                'status' => $refundRequest->status,
            ]);

            return;
        }

        try {
            $user->notify(new RefundStatusUpdated($refundRequest));

            Log::info('Refund status notification sent to user', [
                'refund_request_id' => $refundRequest->id,
                'user_id' => $user->id,
                'status' => $refundRequest->status,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send refund status notification', [
                'refund_request_id' => $refundRequest->id,
                'error' => $e->getMessage(),
            ]);

            // Clear cache on failure to allow retry
            Cache::forget($cacheKey);
        }
    }
}
