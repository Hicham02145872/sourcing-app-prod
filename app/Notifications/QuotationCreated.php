<?php

namespace App\Notifications;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuotationCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $quotation;
    
    public $tries = 3;
    public $backoff = [60, 300, 900]; // 1min, 5min, 15min

    /**
     * Create a new notification instance.
     */
    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    /**
     * Get the notification's delivery channels.
     * Mail + Database uniquement (FCM géré par le listener)
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
        $url = url(route('client.sourcing-requests.show', $this->quotation->sourcingRequest->id));

        return (new MailMessage)
            ->subject('Nouveau devis reçu pour votre demande de sourcing')
            ->greeting('Bonjour,')
            ->line("Un nouveau devis de **{$this->quotation->amount} {$this->quotation->currency}** a été créé pour votre demande de sourcing : **{$this->quotation->sourcingRequest->product_name}**.")
            ->action('Voir le devis', $url)
            ->line('Veuillez consulter les détails et accepter ou rejeter le devis.')
            ->line('Merci d\'utiliser notre service !');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Nouveau devis reçu',
            'body' => 'Un nouveau devis de ' . $this->quotation->amount . ' ' . $this->quotation->currency . ' a été créé pour votre demande de sourcing : ' . $this->quotation->sourcingRequest->product_name,
            'sourcing_request_id' => $this->quotation->sourcing_request_id,
            'click_action' => route('client.sourcing-requests.show', $this->quotation->sourcingRequest->id),
        ];
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): \DateTime
    {
        return now()->addHours(24);
    }
}