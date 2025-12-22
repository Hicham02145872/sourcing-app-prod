<?php

namespace App\Notifications;

use App\Models\RefundRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class RefundStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public RefundRequest $refundRequest)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['mail', 'database'];

        if ($notifiable->fcm_token) {
            $channels[] = 'fcm';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): \App\Mail\RefundApproved|\App\Mail\RefundRejected
    {
        if ($this->refundRequest->status === 'approved') {
            return (new \App\Mail\RefundApproved($this->refundRequest, $notifiable))
                ->to($notifiable->email);
        }

        return (new \App\Mail\RefundRejected($this->refundRequest, $notifiable))
            ->to($notifiable->email);
    }

    /**
     * Get the FCM representation of the notification.
     */
    public function toFcm(object $notifiable): CloudMessage
    {
        $status = $this->refundRequest->status;
        $orderId = $this->refundRequest->sourcingOrder->id;

        $title = $status === 'approved' ? __('Refund Approved') : __('Refund Rejected');
        $body = $status === 'approved'
            ? __('Your refund request for order #:orderId has been approved.', ['orderId' => $orderId])
            : __('Your refund request for order #:orderId has been rejected.', ['orderId' => $orderId]);

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification(FirebaseNotification::create($title, $body))
            ->withData([
                'click_action' => route('client.refund-requests.show', $this->refundRequest->id),
                'refund_request_id' => (string) $this->refundRequest->id,
                'status' => $status,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'refund_request_id' => $this->refundRequest->id,
            'status' => $this->refundRequest->status,
            'title' => $this->refundRequest->status === 'approved' ? __('Refund Approved') : __('Refund Rejected'),
            'message' => __('Your refund request for order #:orderId is now :status.', [
                'orderId' => $this->refundRequest->sourcingOrder->id,
                'status' => $this->refundRequest->status,
            ]),
        ];
    }
}
