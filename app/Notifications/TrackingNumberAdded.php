<?php

namespace App\Notifications;

use App\Models\SourcingOrder;
use App\Notifications\Concerns\UsesNotifiableLocaleRoutes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class TrackingNumberAdded extends Notification implements ShouldQueue
{
    use Queueable;
    use UsesNotifiableLocaleRoutes;

    public $tries = 3;

    public $maxExceptions = 3;

    public $backoff = [60, 300, 900];

    public function __construct(
        protected SourcingOrder $sourcingOrder
    ) {
        $this->afterCommit();
    }

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
        $fsbNumber = $this->sourcingOrder->fsb_tracking_number;
        $productName = $this->sourcingOrder->quotation->sourcingRequest->product_name ?? __('your order');

        return (new MailMessage)
            ->subject(__('Tracking number for your order #:orderId', ['orderId' => $fsbNumber]))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
            ->line(__('A tracking number has been added to your order #:orderId for ":productName".', [
                'orderId' => $fsbNumber,
                'productName' => $productName,
            ]))
            ->line(__('Your tracking reference: :number', ['number' => $fsbNumber]))
            ->action(__('Track your shipment'), $this->localizedClientRoute($notifiable, 'client.tracking.index', ['number' => $fsbNumber]))
            ->line(__('You can also view your order and track it from your dashboard.'));
    }

    public function toArray(object $notifiable): array
    {
        $fsbNumber = $this->sourcingOrder->fsb_tracking_number;
        $productName = $this->sourcingOrder->quotation->sourcingRequest->product_name ?? '';

        return [
            'sourcing_order_id' => $this->sourcingOrder->id,
            'title_key' => '📦 Tracking number for order #:orderId',
            'title_params' => ['orderId' => $fsbNumber],
            'body_key' => 'Your order ":productName" can now be tracked. Your tracking reference: :number',
            'body_params' => [
                'productName' => $productName,
                'number' => $fsbNumber,
            ],
            'type' => 'tracking_added',
            'product_name' => $productName,
            'tracking_number' => $fsbNumber,
            'click_action' => $this->localizedClientRoute($notifiable, 'client.sourcing-orders.show', [
                'sourcingOrder' => $this->sourcingOrder->id,
            ]),
        ];
    }

    public function toFcm(object $notifiable)
    {
        $fsbNumber = $this->sourcingOrder->fsb_tracking_number;
        $url = $this->localizedClientRoute($notifiable, 'client.sourcing-orders.show', [
            'sourcingOrder' => $this->sourcingOrder->id,
        ]);

        $title = __('📦 Tracking for order #:orderId', ['orderId' => $fsbNumber]);
        $body = __('Your tracking reference: :number. Tap to view your order.', ['number' => $fsbNumber]);

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
                'tracking_number' => $fsbNumber,
                'image' => $imageUrl ?? '',
                'order_url' => $url,
                'unread_count' => (string) ($notifiable->unreadNotifications()->count() + 1),
            ]);
    }
}
