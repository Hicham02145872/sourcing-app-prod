<?php

namespace App\Notifications;

use App\Models\SourcingOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class TrackingNumberAdded extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected SourcingOrder $sourcingOrder
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database', 'mail'];

        if ($notifiable->fcm_token ?? null) {
            $channels[] = 'fcm';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $orderId = $this->sourcingOrder->fsb_tracking_number;
        $trackingNumber = $this->sourcingOrder->tracking_number;
        $carrier = $this->sourcingOrder->tracking_carrier ? " ({$this->sourcingOrder->tracking_carrier})" : '';
        $productName = $this->sourcingOrder->quotation->sourcingRequest->product_name ?? __('your order');

        return (new MailMessage)
            ->subject(__('Tracking number for your order #:orderId', ['orderId' => $orderId]))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
            ->line(__('A tracking number has been added to your order #:orderId for ":productName".', [
                'orderId' => $orderId,
                'productName' => $productName,
            ]))
            ->line(__('Tracking number: :number', ['number' => $trackingNumber.$carrier]))
            ->action(__('Track your shipment'), route('client.tracking.index', ['number' => $trackingNumber]))
            ->line(__('You can also view your order and track it from your dashboard.'));
    }

    public function toArray(object $notifiable): array
    {
        $orderId = $this->sourcingOrder->fsb_tracking_number;
        $trackingNumber = $this->sourcingOrder->tracking_number;
        $productName = $this->sourcingOrder->quotation->sourcingRequest->product_name ?? __('your order');

        return [
            'sourcing_order_id' => $this->sourcingOrder->id,
            'title' => __('📦 Tracking number for order #:orderId', ['orderId' => $orderId]),
            'body' => __('Your order ":productName" can now be tracked. Number: :number', [
                'productName' => $productName,
                'number' => $trackingNumber,
            ]),
            'type' => 'tracking_added',
            'product_name' => $productName,
            'tracking_number' => $trackingNumber,
            'click_action' => route('client.sourcing-orders.show', $this->sourcingOrder->id),
        ];
    }

    public function toFcm(object $notifiable)
    {
        $orderId = $this->sourcingOrder->fsb_tracking_number;
        $trackingNumber = $this->sourcingOrder->tracking_number;
        $url = route('client.sourcing-orders.show', $this->sourcingOrder->id);

        $title = __('📦 Tracking for order #:orderId', ['orderId' => $orderId]);
        $body = __('Tracking number: :number. Tap to view your order.', ['number' => $trackingNumber]);

        $notification = FirebaseNotification::create($title, $body);

        $sourcingRequest = $this->sourcingOrder->quotation->sourcingRequest;
        $imageUrl = $sourcingRequest->product_image ? asset('storage/'.$sourcingRequest->product_image) : null;
        if ($imageUrl) {
            $notification = $notification->withImage($imageUrl);
        }

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification($notification)
            ->withData([
                'click_action' => $url,
                'sourcing_order_id' => (string) $this->sourcingOrder->id,
                'tracking_number' => $trackingNumber,
                'image' => $imageUrl ?? '',
                'order_url' => $url,
                'unread_count' => (string) ($notifiable->unreadNotifications()->count() + 1),
            ]);
    }
}
