<?php

namespace App\Notifications;

use App\Models\SourcingOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class ProofOfPaymentUploaded extends Notification implements ShouldQueue
{
    use Queueable;

    public SourcingOrder $sourcingOrder;

    /**
     * Create a new notification instance.
     */
    public function __construct(SourcingOrder $sourcingOrder)
    {
        $this->sourcingOrder = $sourcingOrder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['mail', 'database'];
        if ($notifiable->fcm_token) {
            $channels[] = 'fcm';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('admin.sourcing-orders.show', $this->sourcingOrder->id));

        return (new MailMessage)
            ->subject(__('Proof of Payment Uploaded for Sourcing Order #:orderId', ['orderId' => $this->sourcingOrder->id]))
            ->greeting(__('Hello Admin,'))
            ->line(__('A client has uploaded proof of payment for Sourcing Order #:orderId.', ['orderId' => $this->sourcingOrder->id]))
            ->action(__('View Sourcing Order'), $url)
            ->line(__('Please review the proof of payment and update the order status accordingly.'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $this->sourcingOrder->load('quotation.sourcingRequest', 'user');
        $productName = $this->sourcingOrder->quotation->sourcingRequest->product_name;
        $clientName = $this->sourcingOrder->user->name;

        return [
            'sourcing_order_id' => $this->sourcingOrder->id,
            'title' => __('Payment Uploaded'),
            'body' => __(':clientName uploaded proof of payment for the order related to \':productName\'.', ['clientName' => $clientName, 'productName' => $productName]),
            'link' => route('admin.sourcing-orders.show', $this->sourcingOrder->id),
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
        $url = route('admin.sourcing-orders.show', $this->sourcingOrder->id);

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification(FirebaseNotification::create(
                __('Proof of Payment Uploaded'),
                __('Proof of payment uploaded for Sourcing Order #:orderId', ['orderId' => $this->sourcingOrder->id])
            ))
            ->withData([
                'click_action' => $url,
                'sourcing_order_id' => (string) $this->sourcingOrder->id,
            ]);
    }
}
