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
        $emoji = $this->getStatusEmoji($this->sourcingOrder->status);

        return [
            'sourcing_order_id' => $this->sourcingOrder->id,
            'title' => __(':emoji Order Status Update: :status', ['emoji' => $emoji, 'status' => $statusLabel]),
            'body' => $this->getStatusBody($this->sourcingOrder->status, $statusLabel),
            'type' => 'info',
            'product_name' => $this->sourcingOrder->quotation->sourcingRequest->product_name,
            'click_action' => route('client.sourcing-orders.show', $this->sourcingOrder->id),
        ];
    }

    public function toFcm($notifiable)
    {
        $statusLabel = $this->getStatusLabel($this->sourcingOrder->status);
        $emoji = $this->getStatusEmoji($this->sourcingOrder->status);
        $url = route('client.sourcing-orders.show', $this->sourcingOrder->id);

        $sourcingRequest = $this->sourcingOrder->quotation->sourcingRequest;
        $imageUrl = $sourcingRequest->product_image ? asset('storage/'.$sourcingRequest->product_image) : null;

        $title = __(':emoji Order Status: :status', ['emoji' => $emoji, 'status' => $statusLabel]);
        $body = $this->getStatusBody($this->sourcingOrder->status, $statusLabel);

        $notification = FirebaseNotification::create($title, $body);

        if ($imageUrl) {
            $notification = $notification->withImage($imageUrl);
        }

        $actions = [
            [
                'action' => 'view_order',
                'title' => __('View Order'),
            ],
        ];

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification($notification)
            ->withData([
                'click_action' => $url,
                'sourcing_order_id' => (string) $this->sourcingOrder->id,
                'status' => $this->sourcingOrder->status,
                'image' => $imageUrl ?? '',
                'actions' => json_encode($actions),
                'order_url' => $url,
                'unread_count' => (string) ($notifiable->unreadNotifications()->count() + 1),
            ]);
    }

    private function getStatusEmoji(string $status): string
    {
        return match ($status) {
            'paid' => '✅',
            'shipment_preparing' => '📦',
            'in_transit_china', 'in_transit_uae' => '🚚',
            'arrival_uae' => '🇦🇪',
            'arrival_destination_country' => '📍',
            'out_for_delivery' => '🏁',
            'delivered' => '🎉',
            'delivery_failed' => '⚠️',
            'shipment_delayed' => '⏳',
            'shipment_canceled', 'shipment_returned' => '❌',
            default => '🔔',
        };
    }

    private function getStatusBody(string $status, string $label): string
    {
        if ($status === 'paid') {
            return __('Payment confirmed! Next step: Shipment preparation.');
        }

        if ($status === 'arrival_uae') {
            return __('Great news! Your package has arrived in the UAE.');
        }

        if ($status === 'delivery_failed') {
            return __('Delivery failed. Please check your order details to reschedule.');
        }

        return __('Your order #:orderId is now :status.', [
            'orderId' => $this->sourcingOrder->id,
            'status' => $label,
        ]);
    }

    private function getStatusLabel(string $status): string
    {
        $statusLabels = [
            'pending_payment' => __('Pending payment'),
            'paid' => __('Paid'),
            'shipment_preparing' => __('Shipment Preparing'),
            'in_transit_china' => __('In Transit (Departure from China)'),
            'arrival_uae' => __('Arrival in UAE'),
            'customs_clearance_uae' => __('Customs Clearance in UAE'),
            'in_transit_uae' => __('In Transit (Departure from UAE)'),
            'arrival_destination_country' => __('Arrival in Destination Country'),
            'customs_clearance_destination_country' => __('Customs Clearance in Destination Country'),
            'out_for_delivery' => __('Out for Delivery'),
            'delivered' => __('Delivered'),
            'delivery_failed' => __('Delivery Failed'),
            'shipment_delayed' => __('Shipment Delayed'),
            'shipment_returned' => __('Shipment Returned'),
            'shipment_canceled' => __('Shipment Canceled'),
            'order_completed' => __('Order Completed'),
            'on_hold' => __('On Hold'),
        ];

        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}
