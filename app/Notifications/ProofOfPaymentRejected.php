<?php

namespace App\Notifications;

use App\Models\SourcingOrder;
use App\Notifications\Concerns\UsesNotifiableLocaleRoutes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class ProofOfPaymentRejected extends Notification implements ShouldQueue
{
    use Queueable;
    use UsesNotifiableLocaleRoutes;

    public $tries = 3;

    public $maxExceptions = 3;

    public $backoff = [60, 300, 900];

    public $sourcingOrder;

    /**
     * Create a new notification instance.
     */
    public function __construct(SourcingOrder $sourcingOrder)
    {
        $this->sourcingOrder = $sourcingOrder;
        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];
        if ($notifiable->fcm_token) {
            $channels[] = 'fcm';
        }

        return $channels;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $this->sourcingOrder->load('quotation.sourcingRequest');
        $productName = $this->sourcingOrder->quotation->sourcingRequest->product_name;

        return [
            'sourcing_order_id' => $this->sourcingOrder->id,
            'title_key' => 'Payment Rejected',
            'body_key' => "Your payment for the order related to ':productName' was rejected.",
            'body_params' => ['productName' => $productName],
            'reason' => $this->sourcingOrder->rejection_reason,
            'url' => $this->localizedClientRoute($notifiable, 'client.sourcing-orders.show', [
                'sourcingOrder' => $this->sourcingOrder->id,
            ]),
        ];
    }

    /**
     * Get the FCM representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Kreait\Firebase\Messaging\CloudMessage
     */
    public function toFcm($notifiable)
    {
        $url = $this->localizedClientRoute($notifiable, 'client.sourcing-orders.show', [
            'sourcingOrder' => $this->sourcingOrder->id,
        ]);

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification(FirebaseNotification::create(
                __('Proof of Payment Rejected'),
                __('Your proof of payment for order #:orderId was rejected.', ['orderId' => $this->sourcingOrder->id])
            ))
            ->withData([
                'click_action' => $url,
                'sourcing_order_id' => (string) $this->sourcingOrder->id,
            ]);
    }
}
