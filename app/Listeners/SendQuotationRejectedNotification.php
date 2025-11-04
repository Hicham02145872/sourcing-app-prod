<?php

namespace App\Listeners;

use App\Events\QuotationRejected;
use App\Models\User;
use App\Notifications\QuotationRejected as QuotationRejectedNotification;
use Exception;
use Illuminate\Support\Facades\Log;

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
        $admins = User::where('role', 'admin')->get();
        $quotation = $event->quotation;

        foreach ($admins as $admin) {
            $admin->notify(new QuotationRejectedNotification($quotation));
            $this->sendFcmNotification($quotation, $admin);
        }

        Log::info('QuotationRejected notification sent', [
            'quotation_id' => $quotation->id,
            'sourcing_request_id' => $quotation->sourcing_request_id,
        ]);
    }

    /**
     * Envoie une notification FCM à l'utilisateur
     */
    private function sendFcmNotification($quotation, $user): void
    {
        try {
            $fcmToken = $user->fcm_token;

            if (empty($fcmToken)) {
                Log::debug('No FCM token found for user ' . $user->id);
                return;
            }

            $messaging = app('firebase.messaging');

            $message = \Kreait\Firebase\Messaging\CloudMessage::withTarget('token', $fcmToken)
                ->withNotification(\Kreait\Firebase\Messaging\Notification::create(
                    'Quotation Rejected',
                    'Quotation for SR #' . $quotation->sourcingRequest->id . ' has been rejected.'
                ))
                ->withData([
                    'quotation_id' => (string) $quotation->id,
                    'sourcing_request_id' => (string) $quotation->sourcing_request_id,
                    'click_action' => route('admin.sourcing-requests.show', $quotation->sourcing_request_id),
                ]);

            $messaging->send($message);
            Log::info('FCM notification sent for quotation ' . $quotation->id);
        } catch (Exception $e) {
            Log::error('FCM notification error: ' . $e->getMessage(), [
                'quotation_id' => $quotation->id,
                'user_id' => $user->id,
            ]);
        }
    }
}
