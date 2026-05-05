<?php

namespace App\Notifications;

use App\Models\SourcingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class QuotationNegotiationRequested extends BaseAdminNotification
{

    public function __construct(public SourcingRequest $sourcingRequest)
    {
        $this->afterCommit();
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('admin.sourcing-requests.show', $this->sourcingRequest->id);
        $notes = $this->sourcingRequest->quotation?->negotiation_notes ?? __('No notes provided.');

        return (new MailMessage)
            ->subject(__('Quotation Negotiation Requested: # :requestId', ['requestId' => $this->sourcingRequest->id]))
            ->greeting(__('Hello,'))
            ->line(__('The client has requested a negotiation for the quotation of sourcing request **:productName**.', [
                'productName' => $this->sourcingRequest->product_name,
            ]))
            ->line(__('**Negotiation Notes:**'))
            ->line($notes)
            ->action(__('Review & Re-quote'), $url)
            ->line(__('Please review the notes and update the quotation accordingly.'));
    }

    public function toArray($notifiable): array
    {
        return [
            'title_key' => 'Negotiation Requested',
            'body_key' => "Client requested a negotiation for ':productName' (#:requestId).",
            'body_params' => [
                'productName' => $this->sourcingRequest->product_name,
                'requestId' => $this->sourcingRequest->id,
            ],
            'sourcing_request_id' => $this->sourcingRequest->id,
            'status' => 'negotiating',
            'click_action' => route('admin.sourcing-requests.show', $this->sourcingRequest->id),
        ];
    }
}
