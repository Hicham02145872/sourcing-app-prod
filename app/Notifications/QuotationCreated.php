<?php

namespace App\Notifications;

use App\Models\Quotation;

use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Notifications\Notification;

use Kreait\Firebase\Messaging\CloudMessage;

use Kreait\Firebase\Messaging\Notification as FirebaseNotification;



class QuotationCreated extends Notification implements ShouldQueue

{

    use Queueable;



    protected $quotation;

    

    public $tries = 3;

    public $backoff = [60, 300, 900]; // 1min, 5min, 15min



    public function __construct(Quotation $quotation)

    {

        $this->quotation = $quotation;

    }



    public function via(object $notifiable): array

    {

        $channels = ['mail', 'database'];

        if ($notifiable->fcm_token) {

            $channels[] = 'fcm';

        }

        return $channels;

    }



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



    public function toArray(object $notifiable): array

    {

        return [

            'title' => 'Nouveau devis reçu',

            'body' => 'Un nouveau devis de ' . $this->quotation->amount . ' ' . $this->quotation->currency . ' a été créé pour votre demande de sourcing : ' . $this->quotation->sourcingRequest->product_name,

            'sourcing_request_id' => $this->quotation->sourcing_request_id,

            'click_action' => route('client.sourcing-requests.show', $this->quotation->sourcingRequest->id),

        ];

    }



    public function toFcm(object $notifiable): CloudMessage

    {

        $url = route('client.sourcing-requests.show', $this->quotation->sourcing_request_id);



        return CloudMessage::withTarget('token', $notifiable->fcm_token)

            ->withNotification(FirebaseNotification::create(

                'Nouveau devis reçu',

                "Devis de {$this->quotation->amount} {$this->quotation->currency} pour {$this->quotation->sourcingRequest->product_name}"

            ))

            ->withData([

                'click_action' => $url,

                'quotation_id' => (string) $this->quotation->id,

                'sourcing_request_id' => (string) $this->quotation->sourcing_request_id,

            ]);

    }



    public function retryUntil(): \DateTime

    {

        return now()->addHours(24);

    }

}
