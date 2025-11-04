<?php

namespace App\Listeners;

use App\Events\QuotationAccepted;
use App\Models\User;
use App\Notifications\QuotationAccepted as QuotationAcceptedNotification;
use Exception;
use Illuminate\Support\Facades\Log;

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
        $admins = User::where('role', 'admin')->get();
        $quotation = $event->quotation;

        foreach ($admins as $admin) {
            $admin->notify(new QuotationAcceptedNotification($quotation));
            $this->sendFcmNotification($quotation, $admin);
        }

        Log::info('QuotationAccepted notification sent', [
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
                    'Quotation Accepted',
                    'Quotation for SR #' . $quotation->sourcingRequest->id . ' has been accepted.'
                ))
                ->withData([
                    'quotation_id' => (string) $quotation->id,
                    'sourcing_request_id' => (string) $quotation->sourcing_request_id,
                    'click_action' => route('admin.sourcing-orders.show', $quotation->order->id),
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
