<?php

namespace App\Notifications;

use App\Models\SourcingOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProofOfPaymentUploaded extends Notification
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('admin.sourcing-orders.show', $this->sourcingOrder->id));

        return (new MailMessage)
            ->subject('Proof of Payment Uploaded for Sourcing Order #' . $this->sourcingOrder->id)
            ->greeting('Hello Admin,')
            ->line('A client has uploaded proof of payment for Sourcing Order #' . $this->sourcingOrder->id . '.')
            ->action('View Sourcing Order', $url)
            ->line('Please review the proof of payment and update the order status accordingly.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'sourcing_order_id' => $this->sourcingOrder->id,
            'message' => 'Proof of payment uploaded for Sourcing Order #' . $this->sourcingOrder->id,
            'link' => route('admin.sourcing-orders.show', $this->sourcingOrder->id),
        ];
    }
}
