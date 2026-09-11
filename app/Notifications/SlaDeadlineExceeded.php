<?php

namespace App\Notifications;

use App\Models\SourcingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class SlaDeadlineExceeded extends BaseAdminNotification
{
    use Queueable;

    public function __construct(
        public SourcingRequest $sourcingRequest,
        public int $hours,
    ) {
        $this->afterCommit();
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('sla.deadline_subject', ['id' => $this->sourcingRequest->id]))
            ->greeting(__('Hello ').$notifiable->name.',')
            ->line(__('sla.deadline_mail_line1', [
                'id' => $this->sourcingRequest->id,
                'product' => $this->sourcingRequest->product_name,
                'hours' => $this->hours,
                'status' => $this->sourcingRequest->getStatusLabelAttribute(),
            ]))
            ->line(__('sla.deadline_mail_line2'))
            ->action(__('sla.deadline_mail_action'), route('admin.sourcing-requests.show', $this->sourcingRequest));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'sourcing_request_id' => $this->sourcingRequest->id,
            'title' => __('sla.deadline_db_title', ['id' => $this->sourcingRequest->id]),
            'body' => __('sla.deadline_db_body', [
                'id' => $this->sourcingRequest->id,
                'status' => $this->sourcingRequest->getStatusLabelAttribute(),
            ]),
            'type' => 'sla_deadline_exceeded',
        ];
    }

    public function toFcm(object $notifiable): CloudMessage
    {
        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification(FirebaseNotification::create(
                __('sla.deadline_fcm_title', ['id' => $this->sourcingRequest->id]),
                __('sla.deadline_fcm_body', [
                    'id' => $this->sourcingRequest->id,
                    'status' => $this->sourcingRequest->getStatusLabelAttribute(),
                ])
            ))
            ->withData([
                'click_action' => route('admin.sourcing-requests.show', $this->sourcingRequest->id),
                'sourcing_request_id' => (string) $this->sourcingRequest->id,
            ]);
    }
}