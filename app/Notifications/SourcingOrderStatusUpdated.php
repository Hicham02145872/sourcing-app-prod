<?php

namespace App\Notifications;

use App\Models\SourcingOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SourcingOrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $sourcingOrder;

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
        $statusLabels = [
            'pending_payment' => 'Pending Payment',
            'paid' => 'Paid',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'on_hold' => 'On Hold',
        ];

        $status = $statusLabels[$this->sourcingOrder->status] ?? ucfirst(str_replace('_', ' ', $this->sourcingOrder->status));

        return (new MailMessage)
                    ->subject('Your Sourcing Order Status Has Been Updated')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('The status of your sourcing order #' . $this->sourcingOrder->id . ' for "' . $this->sourcingOrder->quotation->sourcingRequest->product_name . '" has been updated to: ' . $status . '.')
                    ->action('View Your Order', route('client.sourcing-orders.show', $this->sourcingOrder))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $status = ucfirst(str_replace('_', ' ', $this->sourcingOrder->status));

        $notificationData = [
            'sourcing_order_id' => $this->sourcingOrder->id,
            'title' => 'Order Status Update: ' . $status,
            'body' => 'Your order #' . $this->sourcingOrder->id . ' is now ' . $status . '.',
            'type' => 'info',
        ];

        return $notificationData;
    }
}
