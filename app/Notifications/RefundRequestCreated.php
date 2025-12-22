<?php

namespace App\Notifications;

use App\Models\RefundRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class RefundRequestCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public RefundRequest $refundRequest) {}

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
        return (new MailMessage)
            ->subject(__('New Refund Request Created: #').$this->refundRequest->sourcingOrder->id)
            ->greeting(__('Hello ').$notifiable->name.',')
            ->line(__('A new refund request has been created for Order #').$this->refundRequest->sourcingOrder->id.__(' by ').$this->refundRequest->user->name.'.')
            ->line(__('Category: ').$this->refundRequest->reason_category)
            ->line(__('Amount Requested: ').number_format($this->refundRequest->amount_requested, 2).' '.($this->refundRequest->sourcingOrder->quotation->currency ?? 'USD'))
            ->action(__('View Refund Request'), route('admin.refund-requests.show', $this->refundRequest))
            ->line(__('Please review the evidence and approve or reject the request.'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'refund_request_id' => $this->refundRequest->id,
            'title' => __('New Refund Request: #').$this->refundRequest->sourcingOrder->id,
            'body' => __('A new ').$this->refundRequest->type.__(' refund request for ').number_format($this->refundRequest->amount_requested, 2).__(' has been created.'),
            'type' => 'refund_request',
        ];
    }

    public function toFcm(object $notifiable)
    {
        $url = route('admin.refund-requests.show', $this->refundRequest->id);

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification(FirebaseNotification::create(
                __('New Refund Request'),
                __('Order #').$this->refundRequest->sourcingOrder->id.__(': ').$this->refundRequest->reason_category
            ))
            ->withData([
                'click_action' => $url,
                'refund_request_id' => (string) $this->refundRequest->id,
            ]);
    }
}
