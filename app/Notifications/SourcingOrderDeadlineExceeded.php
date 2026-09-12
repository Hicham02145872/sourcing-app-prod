<?php

namespace App\Notifications;

use App\Models\SourcingOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class SourcingOrderDeadlineExceeded extends BaseAdminNotification
{
    use Queueable;

    public function __construct(
        public SourcingOrder $sourcingOrder,
        public int $hours,
        public bool $escalated = false,
    ) {
        $this->afterCommit();
    }

    protected function prefix(): string
    {
        return $this->escalated ? 'order.escalation' : 'order.deadline';
    }

    protected function productName(): string
    {
        return $this->sourcingOrder->quotation?->sourcingRequest?->product_name
            ?? __('Order');
    }

    public function toMail(object $notifiable): MailMessage
    {
        $prefix = $this->prefix();

        return (new MailMessage)
            ->subject(__($prefix.'_subject', ['id' => $this->sourcingOrder->id]))
            ->greeting(__('Hello ').$notifiable->name.',')
            ->line(__($prefix.'_mail_line1', [
                'id' => $this->sourcingOrder->id,
                'product' => $this->productName(),
                'hours' => $this->hours,
                'status' => $this->sourcingOrder->getStatusLabelAttribute(),
            ]))
            ->line(__($prefix.'_mail_line2', [
                'id' => $this->sourcingOrder->id,
                'status' => $this->sourcingOrder->getStatusLabelAttribute(),
            ]))
            ->action(__($prefix.'_mail_action'), route('admin.sourcing-orders.show', $this->sourcingOrder));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'sourcing_order_id' => $this->sourcingOrder->id,
            'title' => __($this->prefix().'_db_title', ['id' => $this->sourcingOrder->id]),
            'body' => __($this->prefix().'_db_body', [
                'id' => $this->sourcingOrder->id,
                'status' => $this->sourcingOrder->getStatusLabelAttribute(),
            ]),
            'type' => $this->escalated ? 'order_deadline_escalated' : 'order_deadline_exceeded',
        ];
    }

    public function toFcm(object $notifiable): CloudMessage
    {
        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification(FirebaseNotification::create(
                __($this->prefix().'_fcm_title', ['id' => $this->sourcingOrder->id]),
                __($this->prefix().'_fcm_body', [
                    'id' => $this->sourcingOrder->id,
                    'status' => $this->sourcingOrder->getStatusLabelAttribute(),
                ])
            ))
            ->withData([
                'click_action' => route('admin.sourcing-orders.show', $this->sourcingOrder->id),
                'sourcing_order_id' => (string) $this->sourcingOrder->id,
            ]);
    }
}
