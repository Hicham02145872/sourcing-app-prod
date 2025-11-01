<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SourcingOrder;

class ProofOfPaymentRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public $sourcingOrder;

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
        return ['database'];
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
            'message' => 'Your proof of payment for order #' . $this->sourcingOrder->id . ' was rejected.',
            'reason' => $this->sourcingOrder->rejection_reason,
            'url' => route('client.sourcing-orders.show', $this->sourcingOrder->id),
        ];
    }
}
