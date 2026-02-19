<?php

namespace App\Notifications;

use App\Models\SourcingOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class FsbTrackingGenerated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected SourcingOrder $sourcingOrder
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database', 'mail'];

        if ($notifiable->fcm_token ?? null) {
            $channels[] = 'fcm';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $fsbNumber = $this->sourcingOrder->fsb_tracking_number;
        $productName = $this->sourcingOrder->quotation->sourcingRequest->product_name ?? __('your order');

        return (new MailMessage)
            ->subject(__('📦 Your FSB Tracking Number is Ready'))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
            ->line(__('Your order ":productName" can now be tracked!', [
                'productName' => $productName,
            ]))
            ->line(__('Your FSB tracking number: **:number**', [
                'number' => $fsbNumber,
            ]))
            ->line(__('You can track your shipment status in real-time using this number.'))
            ->action(__('Track My Shipment'), route('client.tracking.index', ['number' => $fsbNumber]))
            ->line(__('Thank you for choosing FastSourcingBrothers!'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $fsbNumber = $this->sourcingOrder->fsb_tracking_number;
        $productName = $this->sourcingOrder->quotation->sourcingRequest->product_name ?? __('your order');

        return [
            'sourcing_order_id' => $this->sourcingOrder->id,
            'title' => __('📦 FSB Tracking Number Ready'),
            'body' => __('Your order ":productName" can now be tracked. Your FSB tracking number: :number', [
                'productName' => $productName,
                'number' => $fsbNumber,
            ]),
            'type' => 'fsb_tracking_generated',
            'product_name' => $productName,
            'tracking_number' => $fsbNumber,
            'click_action' => route('client.tracking.index', ['number' => $fsbNumber]),
            'order_url' => route('client.sourcing-orders.show', $this->sourcingOrder->id),
        ];
    }

    /**
     * Get the FCM representation of the notification.
     *
     * @return CloudMessage
     */
    public function toFcm(object $notifiable)
    {
        $fsbNumber = $this->sourcingOrder->fsb_tracking_number;
        $url = route('client.tracking.index', ['number' => $fsbNumber]);

        $title = __('📦 FSB Tracking Number Ready');
        $body = __('Your FSB tracking number: :number. Tap to track your shipment.', ['number' => $fsbNumber]);

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
