<?php

namespace App\Notifications;

use App\Models\SourcingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class DossierAssignmentRemoved extends BaseAdminNotification
{
    use Queueable;

    public function __construct(public SourcingRequest $sourcingRequest)
    {
        $this->afterCommit();
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Assignment Removed: Dossier #').$this->sourcingRequest->id)
            ->greeting(__('Hello ').$notifiable->name.',')
            ->line(__('The dossier #').$this->sourcingRequest->id.__(' for product "').$this->sourcingRequest->product_name.__('" is no longer assigned to you.'))
            ->line(__('It has been reassigned by a Super Administrator.'))
            ->action(__('View Sourcing Request'), route('admin.sourcing-requests.show', $this->sourcingRequest))
            ->line(__('Please stop any ongoing work on this folder to avoid double-processing.'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'sourcing_request_id' => $this->sourcingRequest->id,
            'title' => __('Assignment Removed: #').$this->sourcingRequest->id,
            'body' => __('The folder #').$this->sourcingRequest->id.__(' is no longer assigned to you.'),
            'type' => 'assignment_removed',
        ];
    }

    public function toFcm(object $notifiable)
    {
        $url = route('admin.sourcing-requests.show', $this->sourcingRequest->id);

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification(FirebaseNotification::create(
                __('Assignment Removed'),
                __('Dossier #').$this->sourcingRequest->id.__(' is no longer assigned to you.')
            ))
            ->withData([
                'click_action' => $url,
                'sourcing_request_id' => (string) $this->sourcingRequest->id,
            ]);
    }
}
