<?php

namespace App\Notifications;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class QuotationAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $quotation;

    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
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
        $url = url(route('admin.sourcing-orders.show', $this->quotation->order->id));

        return (new MailMessage)
            ->subject('Quotation Accepted for Sourcing Request: ' . $this->quotation->sourcingRequest->product_name)
            ->greeting('Hello Admin,')
            ->line('A quotation for Sourcing Request ' . $this->quotation->sourcingRequest->product_name . ' has been accepted by the client.')
            ->action('View Sourcing Order', $url)
            ->line('Please review the accepted quotation and proceed with the order.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Quotation Accepted',
            'body' => "The quote for '{$this->quotation->sourcingRequest->product_name}' was accepted by {$this->quotation->sourcingRequest->user->name}.",
            'quotation_id' => $this->quotation->id,
            'sourcing_order_id' => $this->quotation->order->id,
            'click_action' => route('admin.sourcing-orders.index'),
        ];
    }

    public function toFcm(object $notifiable): CloudMessage
    {
        $url = route('admin.sourcing-orders.show', $this->quotation->order->id);

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification(FirebaseNotification::create(
                'Quotation Accepted',
                'Quotation for ' . $this->quotation->sourcingRequest->product_name . ' has been accepted.'
            ))
            ->withData([
                'click_action' => $url,
                'quotation_id' => (string) $this->quotation->id,
                'sourcing_request_id' => (string) $this->quotation->sourcing_request_id,
            ]);
    }
}
