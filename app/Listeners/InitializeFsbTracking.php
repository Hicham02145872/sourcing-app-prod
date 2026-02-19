<?php

namespace App\Listeners;

use App\Events\SourcingOrderStatusChanged;
use App\Notifications\FsbTrackingGenerated;
use Illuminate\Support\Facades\Log;

class InitializeFsbTracking
{
    /**
     * Handle the event.
     */
    public function handle(SourcingOrderStatusChanged $event): void
    {
        $order = $event->sourcingOrder;

        // Only initialize FSB tracking when order status changes to 'paid'
        if ($order->status === 'paid' && is_null($order->fsb_tracking_created_at)) {
            $order->update([
                'fsb_tracking_created_at' => now(),
            ]);

            Log::info('🎯 [FSB TRACKING] FSB tracking initialized for order', [
                'order_id' => $order->id,
                'fsb_number' => $order->fsb_tracking_number,
                'status' => $order->status,
            ]);

            // Notify the client that FSB tracking is now available
            if ($order->user) {
                try {
                    $order->user->notify(new FsbTrackingGenerated($order));
                    Log::info('📧 [FSB TRACKING] Notification sent to client', [
                        'order_id' => $order->id,
                        'user_id' => $order->user->id,
                        'fsb_number' => $order->fsb_tracking_number,
                    ]);
                } catch (\Exception $e) {
                    Log::error('❌ [FSB TRACKING] Failed to send notification', [
                        'order_id' => $order->id,
                        'user_id' => $order->user->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }
}
