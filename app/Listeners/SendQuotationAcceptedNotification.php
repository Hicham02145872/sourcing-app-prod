<?php

namespace App\Listeners;

use App\Events\QuotationAccepted;
use App\Models\User;
use App\Notifications\QuotationAccepted as QuotationAcceptedNotification;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SendQuotationAcceptedNotification
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\QuotationAccepted  $event
     * @return void
     */
    public function handle(QuotationAccepted $event)
    {
        $quotation = $event->quotation;
        $lockKey = 'quotation_accepted_notification:' . $quotation->id;

        if (Cache::has($lockKey)) {
            Log::debug('QuotationAccepted notification already sent recently', [
                'quotation_id' => $quotation->id,
                'lock_key' => $lockKey,
            ]);
            return;
        }

        Cache::put($lockKey, true, now()->addSeconds(60));

        try {
            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                $admin->notify(new QuotationAcceptedNotification($quotation));
            }

            Log::info('QuotationAccepted notification sent to all admins', [
                'quotation_id' => $quotation->id,
                'sourcing_request_id' => $quotation->sourcing_request_id,
            ]);
        } catch (Exception $e) {
            Cache::forget($lockKey);
            Log::error('Failed to send QuotationAccepted notification', [
                'quotation_id' => $quotation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
