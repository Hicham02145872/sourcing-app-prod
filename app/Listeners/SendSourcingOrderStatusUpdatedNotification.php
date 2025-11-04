<?php

namespace App\Listeners;

use App\Events\SourcingOrderStatusChanged;
use App\Notifications\SourcingOrderStatusUpdated as SourcingOrderStatusUpdatedNotification;
use Exception;
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

        $user->notify(new SourcingOrderStatusUpdatedNotification($sourcingOrder));

        $this->sendFcmNotification($sourcingOrder, $user);

        Log::info('SourcingOrderStatusUpdated notification sent', [
            'sourcing_order_id' => $sourcingOrder->id,
            'user_id' => $user->id,
            'status' => $sourcingOrder->status,
        ]);
    }

    /**
     * Envoie une notification FCM à l'utilisateur
     */
    private function sendFcmNotification($sourcingOrder, $user): void
    {
        try {
            $fcmToken = $user->fcm_token;

            if (empty($fcmToken)) {
                Log::debug('No FCM token found for user ' . $user->id);
                return;
            }

            $statusLabel = $this->getStatusLabel($sourcingOrder->status);

            $messaging = app('firebase.messaging');

            $message = \Kreait\Firebase\Messaging\CloudMessage::withTarget('token', $fcmToken)
                ->withNotification(\Kreait\Firebase\Messaging\Notification::create(
                    'Mise à jour du statut de votre commande',
                    "Commande #{$sourcingOrder->id} : {$statusLabel}"
                ))
                ->withData([
                    'sourcing_order_id' => (string) $sourcingOrder->id,
                    'status' => $sourcingOrder->status,
                    'click_action' => route('client.sourcing-orders.show', $sourcingOrder->id),
                ]);

            $messaging->send($message);
            Log::info('FCM notification sent for sourcing order ' . $sourcingOrder->id);
        } catch (Exception $e) {
            Log::error('FCM notification error: ' . $e->getMessage(), [
                'sourcing_order_id' => $sourcingOrder->id,
                'user_id' => $user->id,
            ]);
        }
    }

    private function getStatusLabel(string $status): string
    {
        $statusLabels = [
            'pending_payment' => 'En attente de paiement',
            'paid' => 'Payée',
            'shipped' => 'Expédiée',
            'delivered' => 'Livrée',
            'completed' => 'Terminée',
            'cancelled' => 'Annulée',
            'on_hold' => 'En attente',
        ];
        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}
