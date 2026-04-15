<?php

namespace App\Notifications;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class QuotationCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $quotation;

    public $tries = 3;

    public $maxExceptions = 3;

    public $backoff = [60, 300, 900];

    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        $channels = ['database', 'mail'];

        if ($notifiable->fcm_token) {
            $channels[] = 'fcm';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {

        $url = url(route('client.sourcing-requests.show', $this->quotation->sourcingRequest->id));

        return (new MailMessage)
            ->subject(__('New Quotation Received for Your Sourcing Request'))
            ->greeting(__('Hello,'))
            ->line(__('A new quotation of **:amount :currency** has been created for your sourcing request: **:productName**.', [

                'amount' => $this->quotation->amount,

                'currency' => $this->quotation->currency,

                'productName' => $this->quotation->sourcingRequest->product_name,

            ]))
            ->action(__('View Quotation'), $url)
            ->line(__('Please review the details and accept or reject the quotation.'))
            ->line(__('Thank you for using our service!'));

    }

    public function toArray(object $notifiable): array
    {
        return [
            'title_key' => '📄 New Quotation: :amount :currency',
            'title_params' => [
                'amount' => $this->quotation->amount,
                'currency' => $this->quotation->currency,
            ],
            'body_key' => "You've received a new quote for ':productName'. Click to view details.",
            'body_params' => [
                'productName' => $this->quotation->sourcingRequest->product_name,
            ],
            'sourcing_request_id' => $this->quotation->sourcing_request_id,
            'quotation_id' => $this->quotation->id,
            'amount' => $this->quotation->amount,
            'currency' => $this->quotation->currency,
            'product_name' => $this->quotation->sourcingRequest->product_name,
            'click_action' => route('client.sourcing-requests.show', $this->quotation->sourcingRequest->id),
        ];
    }

    public function toFcm(object $notifiable): CloudMessage
    {
        $url = route('client.sourcing-requests.show', $this->quotation->sourcing_request_id);

        $sourcingRequest = $this->quotation->sourcingRequest;
        $imageUrl = $sourcingRequest->product_image ? asset('storage/'.$sourcingRequest->product_image) : null;

        $title = __('📄 New Quotation: :amount :currency', [
            'amount' => $this->quotation->amount,
            'currency' => $this->quotation->currency,
        ]);

        $body = __('New quote received for your request: :productName', [
            'productName' => $sourcingRequest->product_name,
        ]);

        $notification = FirebaseNotification::create($title, $body);

        if ($imageUrl) {
            $notification = $notification->withImage($imageUrl);
        }

        $actions = [
            [
                'action' => 'view_request',
                'title' => __('View Details'),
            ],
        ];

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification($notification)
            ->withData([
                'click_action' => $url,
                'quotation_id' => (string) $this->quotation->id,
                'sourcing_request_id' => (string) $this->quotation->sourcing_request_id,
                'image' => $imageUrl ?? '',
                'actions' => json_encode($actions),
                'request_url' => $url,
                'unread_count' => (string) ($notifiable->unreadNotifications()->count() + 1), // +1 because this one might not be in DB yet if current process
            ]);
    }

    public function failed(\Throwable $exception): void
    {
        \Illuminate\Support\Facades\Log::error('QuotationCreated notification permanently failed', [
            'quotation_id' => $this->quotation->id ?? null,
            'error' => $exception->getMessage(),
        ]);
    }
}
