<?php

namespace App\Notifications;

use App\Notifications\Concerns\UsesNotifiableLocaleRoutes;
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
    use UsesNotifiableLocaleRoutes;

    public $tries = 3;

    public $maxExceptions = 3;

    public $backoff = [60, 300, 900];

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected SourcingOrder $sourcingOrder
    ) {
        $this->afterCommit();
    }

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
        $productName = $this->sourcingOrder->quotation->sourcingRequest->product_name ?? __('your order');
        $fsbNumbers = $this->getFsbNumbers();
        $fsbList = implode(', ', $fsbNumbers);

        $mail = (new MailMessage)
            ->subject(__('📦 Your FSB Tracking Number is Ready'))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
            ->line(__('Your order ":productName" can now be tracked!', [
                'productName' => $productName,
            ]));

        if (count($fsbNumbers) > 1) {
            $mail->line(__('Your FSB tracking numbers: **:numbers**', ['numbers' => $fsbList]));
        } else {
            $mail->line(__('Your FSB tracking number: **:number**', ['number' => $fsbList]));
        }

        return $mail
            ->line(__('You can track your shipment status in real-time using this number.'))
            ->action(__('Track My Shipment'), $this->localizedClientRoute($notifiable, 'client.tracking.index', ['number' => $fsbNumbers[0]]))
            ->line(__('Thank you for choosing FastSourcingBrothers!'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $productName = $this->sourcingOrder->quotation->sourcingRequest->product_name ?? __('your order');
        $fsbNumbers = $this->getFsbNumbers();
        $fsbList = implode(', ', $fsbNumbers);

        $body = count($fsbNumbers) > 1
            ? __('Your order ":productName" can now be tracked. Your FSB tracking numbers: :numbers', ['productName' => $productName, 'numbers' => $fsbList])
            : __('Your order ":productName" can now be tracked. Your FSB tracking number: :number', ['productName' => $productName, 'number' => $fsbList]);

        return [
            'sourcing_order_id' => $this->sourcingOrder->id,
            'title' => __('📦 FSB Tracking Number Ready'),
            'body' => $body,
            'type' => 'fsb_tracking_generated',
            'product_name' => $productName,
            'tracking_number' => $fsbNumbers[0],
            'tracking_numbers' => $fsbNumbers,
            'click_action' => $this->localizedClientRoute($notifiable, 'client.tracking.index', ['number' => $fsbNumbers[0]]),
            'order_url' => $this->localizedClientRoute($notifiable, 'client.sourcing-orders.show', ['sourcingOrder' => $this->sourcingOrder->id]),
        ];
    }

    /**
     * Get the FCM representation of the notification.
     *
     * @return CloudMessage
     */
    public function toFcm(object $notifiable)
    {
        $fsbNumbers = $this->getFsbNumbers();
        $fsbList = implode(', ', $fsbNumbers);
        $url = $this->localizedClientRoute($notifiable, 'client.tracking.index', ['number' => $fsbNumbers[0]]);

        $title = __('📦 FSB Tracking Number Ready');
        $body = count($fsbNumbers) > 1
            ? __('Your FSB tracking numbers: :numbers. Tap to track your shipment.', ['numbers' => $fsbList])
            : __('Your FSB tracking number: :number. Tap to track your shipment.', ['number' => $fsbList]);

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
                'tracking_number' => $fsbNumbers[0],
                'image' => $imageUrl ?? '',
                'order_url' => $url,
                'unread_count' => (string) ($notifiable->unreadNotifications()->count() + 1),
            ]);
    }

    /**
     * Build the list of FSB numbers for this order (one per destination, or just the main one).
     *
     * @return string[]
     */
    protected function getFsbNumbers(): array
    {
        $this->sourcingOrder->loadMissing('quotation.sourcingRequest.destinations');

        if ($this->sourcingOrder->hasMultipleDestinations()) {
            $destinations = $this->sourcingOrder->quotation->sourcingRequest->destinations;
            $numbers = [];
            foreach ($destinations as $index => $dest) {
                $numbers[] = $this->sourcingOrder->getFsbTrackingNumberForDestinationIndex($index);
            }
            return $numbers;
        }

        return [$this->sourcingOrder->fsb_tracking_number];
    }
}
