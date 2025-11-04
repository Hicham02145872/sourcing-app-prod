<?php

namespace App\Listeners;

use App\Events\SourcingRequestStatusChanged;
use App\Notifications\SourcingRequestStatusUpdated as SourcingRequestStatusUpdatedNotification;
use Exception;
use Illuminate\Support\Facades\Log;

class SendSourcingRequestStatusChangeNotification
{
    /**
     * Handle the event.
     */
    public function handle(SourcingRequestStatusChanged $event): void
    {
        $sourcingRequest = $event->sourcingRequest;
        $user = $sourcingRequest->user;

        // Envoie la notification via les canaux configurés (Mail + Database)
        $user->notify(new SourcingRequestStatusUpdatedNotification($sourcingRequest));

        // FCM commenté temporairement - à réactiver une fois Firebase configuré
        $this->sendFcmNotification($sourcingRequest, $user);

        Log::info('SourcingRequestStatusUpdated notification sent', [
            'sourcing_request_id' => $sourcingRequest->id,
            'user_id' => $user->id,
            'status' => $sourcingRequest->status,
        ]);
    }

    /**
     * Envoie une notification FCM à l'utilisateur
     * À RÉACTIVER UNE FOIS FIREBASE CONFIGURÉ
     */
    private function sendFcmNotification($sourcingRequest, $user): void
    {
        try {
            $fcmToken = $user->fcm_token;

            if (empty($fcmToken)) {
                Log::debug('No FCM token found for user ' . $user->id);
                return;
            }

            $statusLabel = $this->getStatusLabel($sourcingRequest->status);

            $messaging = app('firebase.messaging');

            $message = \Kreait\Firebase\Messaging\CloudMessage::withTarget('token', $fcmToken)
                ->withNotification(\Kreait\Firebase\Messaging\Notification::create(
                    'Mise à jour de votre demande de sourcing',
                    "Demande #{$sourcingRequest->product_name} : {$statusLabel}"
                ))
                ->withData([
                    'sourcing_request_id' => (string) $sourcingRequest->id,
                    'status' => $sourcingRequest->status,
                    'product_name' => $sourcingRequest->product_name,
                    'click_action' => route('client.sourcing-requests.show', $sourcingRequest->id),
                ]);

            $messaging->send($message);
            Log::info('FCM notification sent for sourcing request ' . $sourcingRequest->id);
        } catch (Exception $e) {
            Log::error('FCM notification error: ' . $e->getMessage(), [
                'sourcing_request_id' => $sourcingRequest->id,
                'user_id' => $user->id,
            ]);
        }
    }

    private function getStatusLabel(string $status): string
    {
        $statusLabels = [
            'pending' => 'En attente',
            'in_review' => 'En cours de révision',
            'quoted' => 'Devis envoyé',
            'rejected' => 'Rejetée',
            'accepted' => 'Acceptée',
            'cancelled' => 'Annulée',
        ];
        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}