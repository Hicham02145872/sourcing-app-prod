<?php

namespace App\Notifications;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuotationCreated extends Notification
{
    use Queueable;

    protected $quotation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
     *
     * @return array<string, mixed>
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
     * Get the FCM representation of the notification.
     *
     * @return \Kreait\Firebase\Messaging\Message
     */    public function toFcm(object $notifiable)
    {
        $url = route('client.sourcing-requests.show', $this->quotation->sourcingRequest->id);
        $notification = \Kreait\Firebase\Messaging\Notification::create(
            'Nouveau devis reçu',
            'Un nouveau devis de ' . $this->quotation->amount . ' ' . $this->quotation->currency . ' a été créé pour votre demande de sourcing : ' . $this->quotation->sourcingRequest->product_name
        );
        $data = [
            'sourcing_request_id' => (string) $this->quotation->sourcing_request_id,
            'click_action' => $url,
        ];
        return \Kreait\Firebase\Messaging\CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification($notification)
            ->withData($data);
    }
}
