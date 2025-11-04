<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SourcingRequest;

class SourcingRequestStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $sourcingRequest;

    public function __construct(SourcingRequest $sourcingRequest)
    {
        $this->sourcingRequest = $sourcingRequest;
    }

    /**
     * Get the notification's delivery channels.
     * FCM retiré temporairement - garde seulement Mail et Database
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = $this->getStatusLabel($this->sourcingRequest->status);
        $url = url(route('client.sourcing-requests.show', $this->sourcingRequest->id));

        return (new MailMessage)
            ->subject('Mise à jour du statut de votre demande de sourcing #' . $this->sourcingRequest->id)
            ->greeting('Bonjour,')
            ->line("Le statut de votre demande de sourcing #{$this->sourcingRequest->id} ({$this->sourcingRequest->product_name}) a été mis à jour.")
            ->line("Nouveau statut : **{$statusLabel}**")
            ->action('Voir votre demande', $url)
            ->line('Merci d\'utiliser notre service !');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $statusLabel = $this->getStatusLabel($this->sourcingRequest->status);

        return [
            'title' => "Mise à jour de votre demande de sourcing",
            'body' => "Votre demande #{$this->sourcingRequest->id} ({$this->sourcingRequest->product_name}) a été mise à jour au statut : {$statusLabel}.",
            'click_action' => route('client.sourcing-requests.show', $this->sourcingRequest->id),
            'sourcing_request_id' => $this->sourcingRequest->id,
            'status' => $this->sourcingRequest->status,
        ];
    }

    protected function getStatusLabel(string $status): string
    {
        $statusLabels = [
            'pending' => 'En attente',
            'in_review' => 'En cours de révision',
            'quoted' => 'Devis envoyé',
            'rejected' => 'Rejetée',
            'accepted' => 'Acceptée',
            'cancelled' => 'Annulée',
        ];
        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}