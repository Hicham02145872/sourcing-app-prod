<?php

namespace App\Listeners;

use App\Events\QuotationCreated;
use App\Notifications\QuotationCreated as QuotationCreatedNotification;
use Illuminate\Support\Facades\Log;
use Exception;

class SendQuotationCreatedNotification
{
    public $tries = 3;
    public $backoff = [60, 300, 900];

    /**
     * Handle the event.
     */
    public function handle(QuotationCreated $event): void
    {
        $quotation = $event->quotation;
        $user = $quotation->sourcingRequest->user;

        // Envoie la notification via les canaux configurés (Mail + Database)
        $user->notify(new QuotationCreatedNotification($quotation));

        // Envoie le FCM manuellement
        $this->sendFcmNotification($quotation, $user);

        Log::info('QuotationCreated notification sent', [
            'quotation_id' => $quotation->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Envoie une notification FCM à l'utilisateur
     */
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
            ->withNotification(
                \Kreait\Firebase\Messaging\Notification::create(
                    'Nouveau devis reçu',
                    "Devis de {$quotation->amount} {$quotation->currency} pour {$quotation->sourcingRequest->product_name}"
                )
            )
            ->withData([
                'quotation_id' => (string) $quotation->id,
                'sourcing_request_id' => (string) $quotation->sourcing_request_id,
                'amount' => (string) $quotation->amount,
                'currency' => $quotation->currency,
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