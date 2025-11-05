<?php

namespace App\Notifications;

use App\Models\SourcingOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class SourcingOrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $sourcingOrder;

    public function __construct(SourcingOrder $sourcingOrder)
    {
        $this->sourcingOrder = $sourcingOrder;
    }

    public function via(object $notifiable): array
    {
        $channels = ['mail', 'database'];
        if ($notifiable->fcm_token) {
            $channels[] = 'fcm';
        }
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = $this->getStatusLabel($this->sourcingOrder->status);

        return (new MailMessage)
                    ->subject('Your Sourcing Order Status Has Been Updated')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('The status of your sourcing order #' . $this->sourcingOrder->id . ' for "' . $this->sourcingOrder->quotation->sourcingRequest->product_name . '" has been updated to: ' . $statusLabel . '.')
                    ->action('View Your Order', route('client.sourcing-orders.show', $this->sourcingOrder))
                    ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        $statusLabel = $this->getStatusLabel($this->sourcingOrder->status);

        return [
            'sourcing_order_id' => $this->sourcingOrder->id,
            'title' => 'Order Status Update: ' . $statusLabel,
            'body' => 'Your order #' . $this->sourcingOrder->id . ' is now ' . $statusLabel . '.',
            'type' => 'info',
        ];
    }

    public function toFcm($notifiable)
    {
        $statusLabel = $this->getStatusLabel($this->sourcingOrder->status);
        $url = route('client.sourcing-orders.show', $this->sourcingOrder->id);

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification(FirebaseNotification::create(
                'Mise à jour du statut de votre commande',
                "Commande #{$this->sourcingOrder->id} : {$statusLabel}"
            ))
            ->withData([
                'click_action' => $url,
                'sourcing_order_id' => (string) $this->sourcingOrder->id,
            ]);
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
