<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SourcingRequest;

class SourcingRequestStatusUpdated extends Notification
{
    use Queueable;

    protected $sourcingRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(SourcingRequest $sourcingRequest)
    {
        $this->sourcingRequest = $sourcingRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'pending' => 'En attente',
            'handling' => 'En traitement',
            'completed' => 'Terminée',
            'cancelled' => 'Annulée',
        ];
        $statusLabel = $statusLabels[$this->sourcingRequest->status] ?? ucfirst($this->sourcingRequest->status);

        return [
            'title' => "Mise à jour de votre demande de sourcing",
            'body' => "Votre demande #{$this->sourcingRequest->id} ({$this->sourcingRequest->product_name}) a été mise à jour au statut : {$statusLabel}.",
            'click_action' => route('client.sourcing-requests.show', $this->sourcingRequest->id),
            'sourcing_request_id' => $this->sourcingRequest->id,
            'status' => $this->sourcingRequest->status,
        ];
    }
}
