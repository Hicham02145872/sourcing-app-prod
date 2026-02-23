<?php

namespace App\Notifications;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class QuotationRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $maxExceptions = 3;

    public $backoff = [60, 300, 900];

    public $quotation;

    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
        $this->afterCommit();
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

        $url = url(route('admin.sourcing-requests.show', $this->quotation->sourcingRequest->id));

        return (new MailMessage)
            ->subject(__('Quotation Rejected for Sourcing Request: :productName', ['productName' => $this->quotation->sourcingRequest->product_name]))
            ->greeting(__('Hello Admin,'))
            ->line(__('A quotation for Sourcing Request :productName has been rejected by the client.', ['productName' => $this->quotation->sourcingRequest->product_name]))
            ->action(__('View Sourcing Request'), $url)
            ->line(__('Please review the rejected quotation.'));

    }

    public function toArray(object $notifiable): array
    {

        return [

            'quotation_id' => $this->quotation->id,

            'sourcing_request_id' => $this->quotation->sourcing_request_id,

            'title' => __('Quotation Rejected'),

            'body' => __('The quote for \':productName\' was rejected by the client.', ['productName' => $this->quotation->sourcingRequest->product_name]),

            'click_action' => route('admin.sourcing-requests.show', $this->quotation->sourcing_request_id),

            'type' => 'warning',

        ];

    }

    public function toFcm(object $notifiable): CloudMessage
    {

        $url = route('admin.sourcing-requests.show', $this->quotation->sourcing_request_id);

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification(FirebaseNotification::create(

                __('Quotation Rejected'),

                __('Quotation for :productName has been rejected.', ['productName' => $this->quotation->sourcingRequest->product_name])

            ))

            ->withData([

                'click_action' => $url,

                'quotation_id' => (string) $this->quotation->id,

                'sourcing_request_id' => (string) $this->quotation->sourcing_request_id,

            ]);

    }
}
