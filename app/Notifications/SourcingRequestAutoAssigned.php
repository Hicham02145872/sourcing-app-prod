<?php

namespace App\Notifications;

use App\Models\SourcingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SourcingRequestAutoAssigned extends Notification
{
    use Queueable;

    public function __construct(public SourcingRequest $sourcingRequest, public int $workload)
    {
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail', 'fcm'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🤖 Attribution Automatique : '.$this->sourcingRequest->product_name)
            ->greeting('Bonjour '.$notifiable->name.',')
            ->line('Le système vous a automatiquement attribué un nouveau dossier de sourcing en fonction de votre charge de travail actuelle.')
            ->line('Produit : '.$this->sourcingRequest->product_name)
            ->line('Charge actuelle : '.$this->workload.' dossier(s) actif(s).')
            ->action('Traiter le dossier', route('admin.sourcing-requests.show', $this->sourcingRequest))
            ->line('Merci pour votre réactivité.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'sourcing_request_id' => $this->sourcingRequest->id,
            'title' => '🤖 Attribution Automatique',
            'body' => 'Dossier #'.$this->sourcingRequest->id.' attribué automatiquement (Charge: '.$this->workload.').',
            'type' => 'info',
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => '🤖 Nouveau dossier (Auto)',
            'body' => 'Dossier #'.$this->sourcingRequest->id.' attribué (Charge: '.$this->workload.').',
            'data' => [
                'sourcing_request_id' => (string) $this->sourcingRequest->id,
                'click_action' => 'VIEW_SOURCING_REQUEST',
                'url' => route('admin.sourcing-requests.show', $this->sourcingRequest),
            ],
        ];
    }
}
