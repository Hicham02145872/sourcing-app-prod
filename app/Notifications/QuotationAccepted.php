<?php

namespace App\Notifications;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class QuotationAccepted extends BaseAdminNotification
{
    use Queueable;

    protected $quotation;

    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
        $this->afterCommit();
    }



    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('admin.sourcing-orders.show', $this->quotation->order->id));

        return (new MailMessage)
            ->subject(__('Quotation Accepted for Sourcing Request: :productName', ['productName' => $this->quotation->sourcingRequest->product_name]))
            ->greeting(__('Hello Admin,'))
            ->line(__('A quotation for Sourcing Request :productName has been accepted by the client.', ['productName' => $this->quotation->sourcingRequest->product_name]))
            ->action(__('View Sourcing Order'), $url)
            ->line(__('Please review the accepted quotation and proceed with the order.'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title_key' => 'Quotation Accepted',
            'body_key' => "The quote for ':productName' was accepted by :clientName.",
            'body_params' => [
                'productName' => $this->quotation->sourcingRequest->product_name,
                'clientName' => $this->quotation->sourcingRequest->user->name,
            ],
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
                __('Quotation Accepted'),
                __('Quotation for :productName has been accepted.', ['productName' => $this->quotation->sourcingRequest->product_name])
            ))
            ->withData([
                'click_action' => $url,
                'quotation_id' => (string) $this->quotation->id,
                'sourcing_request_id' => (string) $this->quotation->sourcing_request_id,
            ]);
    }
}
