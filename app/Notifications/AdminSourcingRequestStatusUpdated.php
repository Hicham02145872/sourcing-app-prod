<?php

namespace App\Notifications;

use App\Models\SourcingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminSourcingRequestStatusUpdated extends Notification
{
    use Queueable;

    protected $sourcingRequest;

    public function __construct(SourcingRequest $sourcingRequest)
    {
        $this->sourcingRequest = $sourcingRequest;
        $this->afterCommit();
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $statusLabel = ucfirst(str_replace('_', ' ', $this->sourcingRequest->status));
        $url = route('admin.sourcing-requests.show', $this->sourcingRequest->id);

        $message = (new MailMessage)
            ->subject(__('Sourcing Request Update: # :requestId', ['requestId' => $this->sourcingRequest->id]))
            ->greeting(__('Hello,'))
            ->line(__('The client has updated the status of sourcing request **:productName**.', ['productName' => $this->sourcingRequest->product_name]))
            ->line(__('New Status: **:status**', ['status' => $statusLabel]));

        if ($this->sourcingRequest->status === 'negotiating' && $this->sourcingRequest->quotation) {
            $message->line(__('Negotiation Notes: :notes', ['notes' => $this->sourcingRequest->quotation->negotiation_notes]));
        }

        return $message->action(__('View Request'), $url);
    }

    public function toArray($notifiable): array
    {
        $statusLabel = ucfirst(str_replace('_', ' ', $this->sourcingRequest->status));

        return [
            'title' => __('Sourcing Request Status Updated by Client'),
            'body' => __('Request # :requestId for \':productName\' is now \':status\'.', [
                'requestId' => $this->sourcingRequest->id,
                'productName' => $this->sourcingRequest->product_name,
                'status' => $statusLabel,
            ]),
            'sourcing_request_id' => $this->sourcingRequest->id,
            'status' => $this->sourcingRequest->status,
            'click_action' => route('admin.sourcing-requests.show', $this->sourcingRequest->id),
        ];
    }
}
