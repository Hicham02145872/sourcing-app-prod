<?php

namespace App\Listeners;

use App\Events\SourcingOrderStatusChanged;
use App\Notifications\SourcingOrderStatusUpdated as SourcingOrderStatusUpdatedNotification;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendSourcingOrderStatusUpdatedNotification
{
    /**
     * Handle the event.
     */
    public function handle(SourcingOrderStatusChanged $event): void
    {
        $sourcingOrder = $event->sourcingOrder;
        $user = $sourcingOrder->quotation->sourcingRequest->user;

        $lockKey = 'sourcing_order_notification:'.$sourcingOrder->id.':'.$sourcingOrder->status;

        if (Cache::has($lockKey)) {
            Log::debug('SourcingOrderStatusUpdated notification already sent recently', [
                'sourcing_order_id' => $sourcingOrder->id,
                'lock_key' => $lockKey,
            ]);

            return;
        }

        Cache::put($lockKey, true, now()->addSeconds(60));

        try {
            $user->notify(new SourcingOrderStatusUpdatedNotification($sourcingOrder));

            Log::info('SourcingOrderStatusUpdated notification sent for Sourcing Order', [
                'sourcing_order_id' => $sourcingOrder->id,
                'user_id' => $user->id,
                'status' => $sourcingOrder->status,
            ]);
        } catch (Exception $e) {
            Cache::forget($lockKey);
            Log::error('Failed to send SourcingOrderStatusUpdated notification.', [
                'sourcing_order_id' => $sourcingOrder->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
