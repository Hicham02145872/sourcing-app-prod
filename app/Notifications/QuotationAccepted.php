<?php

namespace App\Notifications;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuotationAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $quotation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
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

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('admin.sourcing-orders.show', $this->quotation->order->id));

        return (new MailMessage)
            ->subject('Quotation Accepted for Sourcing Request #' . $this->quotation->sourcingRequest->id)
            ->greeting('Hello Admin,')
            ->line('A quotation for Sourcing Request #' . $this->quotation->sourcingRequest->id . ' has been accepted by the client.')
            ->action('View Sourcing Order', $url)
            ->line('Please review the accepted quotation and proceed with the order.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Quotation Accepted',
            'body' => 'Quotation #' . $this->quotation->id . ' for ' . $this->quotation->sourcingRequest->product_name . ' has been accepted by ' . $this->quotation->sourcingRequest->user->name . '.',
            'quotation_id' => $this->quotation->id,
            'sourcing_order_id' => $this->quotation->order->id, // Assuming order is already created
            'click_action' => route('admin.sourcing-orders.index'),
        ];
    }
}
