<?php

namespace App\Notifications;

use App\Models\SourcingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SourcingRequestAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public SourcingRequest $sourcingRequest) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'fcm'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouveau dossier assigné : '.$this->sourcingRequest->product_name)
            ->greeting('Bonjour '.$notifiable->name.',')
            ->line('Un nouveau dossier de sourcing vous a été assigné.')
            ->line('Produit : '.$this->sourcingRequest->product_name)
            ->action('Voir le dossier', route('admin.sourcing-requests.show', $this->sourcingRequest))
            ->line('Merci de traiter cette demande dès que possible.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'sourcing_request_id' => $this->sourcingRequest->id,
            'title' => 'Nouveau dossier assigné',
            'body' => 'Le dossier #'.$this->sourcingRequest->id.' ('.$this->sourcingRequest->product_name.') vous a été assigné.',
            'type' => 'info',
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => '📥 Nouveau dossier assigné',
            'body' => 'Le dossier #'.$this->sourcingRequest->id.' ('.$this->sourcingRequest->product_name.') vous a été assigné.',
            'data' => [
                'sourcing_request_id' => (string) $this->sourcingRequest->id,
                'click_action' => 'VIEW_SOURCING_REQUEST',
                'url' => route('admin.sourcing-requests.show', $this->sourcingRequest),
            ],
        ];
    }
}
