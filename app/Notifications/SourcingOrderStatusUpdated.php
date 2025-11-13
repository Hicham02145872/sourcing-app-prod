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
                    ->subject(__('Your Sourcing Order Status Has Been Updated'))
                    ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
                    ->line(__('The status of your sourcing order #:orderId for ":productName" has been updated to: :status.', [
                        'orderId' => $this->sourcingOrder->id,
                        'productName' => $this->sourcingOrder->quotation->sourcingRequest->product_name,
                        'status' => $statusLabel,
                    ]))
                    ->action(__('View Your Order'), route('client.sourcing-orders.show', $this->sourcingOrder))
                    ->line(__('Thank you for using our application!'));
    }

    public function toArray(object $notifiable): array
    {
        $statusLabel = $this->getStatusLabel($this->sourcingOrder->status);

        return [
            'sourcing_order_id' => $this->sourcingOrder->id,
            'title' => __('Order Status Update: :status', ['status' => $statusLabel]),
            'body' => __('Your order #:orderId is now :status.', ['orderId' => $this->sourcingOrder->id, 'status' => $statusLabel]),
            'type' => 'info',
        ];
    }

    public function toFcm($notifiable)
    {
        $statusLabel = $this->getStatusLabel($this->sourcingOrder->status);
        $url = route('client.sourcing-orders.show', $this->sourcingOrder->id);

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification(FirebaseNotification::create(
                __('Order Status Update'),
                __('Order #:orderId : :status', ['orderId' => $this->sourcingOrder->id, 'status' => $statusLabel])
            ))
            ->withData([
                'click_action' => $url,
                'sourcing_order_id' => (string) $this->sourcingOrder->id,
            ]);
    }

    private function getStatusLabel(string $status): string
    {
        $statusLabels = [
            'pending_payment' => __('Pending payment'),
            'paid' => __('Paid'),
            'shipped' => __('Shipped'),
            'delivered' => __('Delivered'),
            'completed' => __('Completed'),
            'cancelled' => __('Cancelled'),
            'on_hold' => __('On Hold'),
        ];
        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}
