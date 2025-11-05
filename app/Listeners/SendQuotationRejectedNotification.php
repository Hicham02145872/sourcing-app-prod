<?php

namespace App\Listeners;

use App\Events\QuotationRejected;
use App\Models\User;
use App\Notifications\QuotationRejected as QuotationRejectedNotification;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SendQuotationRejectedNotification
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\QuotationRejected  $event
     * @return void
     */
    public function handle(QuotationRejected $event)
    {
        $quotation = $event->quotation;
        $lockKey = 'quotation_rejected_notification:' . $quotation->id;

        if (Cache::has($lockKey)) {
            Log::debug('QuotationRejected notification already sent recently', [
                'quotation_id' => $quotation->id,
                'lock_key' => $lockKey,
            ]);
            return;
        }

        Cache::put($lockKey, true, now()->addSeconds(60));

        try {
            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                $admin->notify(new QuotationRejectedNotification($quotation));
            }

            Log::info('QuotationRejected notification sent to all admins', [
                'quotation_id' => $quotation->id,
                'sourcing_request_id' => $quotation->sourcing_request_id,
            ]);
        } catch (Exception $e) {
            Cache::forget($lockKey);
            Log::error('Failed to send QuotationRejected notification', [
                'quotation_id' => $quotation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
