<?php

namespace App\Notifications;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class QuotationAccepted extends BaseAdminNotification
{
    use Queueable;

    protected $quotation;

    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation->loadMissing(['order', 'sourcingRequest.user']);
        $this->afterCommit();
    }

    public function toMail(object $notifiable): MailMessage
    {
        $sourcingRequest = $this->quotation->sourcingRequest;
        $productName = $sourcingRequest?->product_name ?? __('the request');
        $url = $this->sourcingOrderUrl($notifiable, 'toMail');

        return (new MailMessage)
            ->subject(__('Quotation Accepted for Sourcing Request: :productName', ['productName' => $productName]))
            ->greeting(__('Hello Admin,'))
            ->line(__('A quotation for Sourcing Request :productName has been accepted by the client.', ['productName' => $productName]))
            ->action(__('View Sourcing Order'), $url)
            ->line(__('Please review the accepted quotation and proceed with the order.'));
    }

    public function toArray(object $notifiable): array
    {
        $sourcingRequest = $this->quotation->sourcingRequest;
        $clientName = $sourcingRequest?->user?->name ?? __('a client');
        $orderId = $this->quotation->order?->id;

        if (! $sourcingRequest?->user) {
            $this->logMissingRelation('sourcingRequest.user', $notifiable);
        }

        if (! $this->quotation->order) {
            $this->logMissingRelation('order', $notifiable);
        }

        return [
            'title_key' => 'Quotation Accepted',
            'body_key' => "The quote for ':productName' was accepted by :clientName.",
            'body_params' => [
                'productName' => $sourcingRequest?->product_name ?? __('the request'),
                'clientName' => $clientName,
            ],
            'quotation_id' => $this->quotation->id,
            'sourcing_order_id' => $orderId,
            'click_action' => route('admin.sourcing-orders.index'),
        ];
    }

    public function toFcm(object $notifiable): CloudMessage
    {
        $url = $this->sourcingOrderUrl($notifiable, 'toFcm');
        $productName = $this->quotation->sourcingRequest?->product_name ?? __('the request');

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification(FirebaseNotification::create(
                __('Quotation Accepted'),
                __('Quotation for :productName has been accepted.', ['productName' => $productName])
            ))
            ->withData([
                'click_action' => $url,
                'quotation_id' => (string) $this->quotation->id,
                'sourcing_request_id' => (string) $this->quotation->sourcing_request_id,
            ]);
    }

    protected function sourcingOrderUrl(object $notifiable, string $context): string
    {
        if ($this->quotation->order?->id) {
            return route('admin.sourcing-orders.show', $this->quotation->order->id);
        }

        $this->logMissingRelation('order', $notifiable, $context);

        return route('admin.sourcing-orders.index');
    }

    protected function logMissingRelation(string $relation, object $notifiable, string $context = 'toArray'): void
    {
        Log::warning('QuotationAccepted notification missing relation', [
            'relation_missing' => $relation,
            'context' => $context,
            'quotation_id' => $this->quotation->id ?? null,
            'user_id' => $notifiable->id ?? null,
            'sourcing_request_id' => $this->quotation->sourcing_request_id ?? null,
        ]);
    }
}
