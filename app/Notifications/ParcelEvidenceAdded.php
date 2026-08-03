<?php

namespace App\Notifications;

use App\Models\SourcingOrder;
use App\Notifications\Concerns\UsesNotifiableLocaleRoutes;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class ParcelEvidenceAdded extends Notification
{
    use Queueable;
    use UsesNotifiableLocaleRoutes;

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
        $orderRef = $this->sourcingOrder->reference_id;
        $productName = $this->sourcingOrder->quotation->sourcingRequest->product_name ?? __('your order');

        return (new MailMessage)
            ->subject(__('Parcel photo added for order #:orderRef', ['orderRef' => $orderRef]))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
            ->line(__('A photo of your parcel for order #:orderRef (":productName") has been added by our team.', [
                'orderRef' => $orderRef,
                'productName' => $productName,
            ]))
            ->line(__('You can view the parcel photo in your order details.'))
            ->action(__('View your order'), $this->localizedClientRoute($notifiable, 'client.sourcing-orders.show', [
                'sourcingOrder' => $this->sourcingOrder->id,
            ]));
    }

    public function toArray(object $notifiable): array
    {
        $orderRef = $this->sourcingOrder->reference_id;
        $productName = $this->sourcingOrder->quotation->sourcingRequest->product_name ?? '';
        $url = $this->localizedClientRoute($notifiable, 'client.sourcing-orders.show', [
            'sourcingOrder' => $this->sourcingOrder->id,
        ]);

        return [
            'sourcing_order_id' => $this->sourcingOrder->id,
            'title_key' => '📦 Parcel photo added for order #:orderRef',
            'title_params' => ['orderRef' => $orderRef],
            'body_key' => 'The photo of your parcel for order ":productName" is now available. Tap to view it.',
            'body_params' => [
                'productName' => $productName,
            ],
            'type' => 'parcel_evidence_added',
            'product_name' => $productName,
            'click_action' => $url,
            'link' => $url,
        ];
    }

    public function toFcm(object $notifiable)
    {
        $orderRef = $this->sourcingOrder->reference_id;
        $url = $this->localizedClientRoute($notifiable, 'client.sourcing-orders.show', [
            'sourcingOrder' => $this->sourcingOrder->id,
        ]);

        $title = __('📦 Parcel photo added for order #:orderRef', ['orderRef' => $orderRef]);
        $body = __('The photo of your parcel is now available. Tap to view it.');

        $notification = FirebaseNotification::create($title, $body);

        $sourcingRequest = $this->sourcingOrder->quotation->sourcingRequest;
        $imageUrl = $sourcingRequest->product_image ? media_url($sourcingRequest->product_image) : null;

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification($notification)
            ->withData([
                'click_action' => $url,
                'sourcing_order_id' => (string) $this->sourcingOrder->id,
                'image' => $imageUrl ?? '',
                'order_url' => $url,
                'unread_count' => (string) ($notifiable->unreadNotifications()->count() + 1),
            ]);
    }
}
