<?php

namespace App\Notifications;

use App\Models\Quotation;

use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Notifications\Notification;

use Kreait\Firebase\Messaging\CloudMessage;

use Kreait\Firebase\Messaging\Notification as FirebaseNotification;



class QuotationRejected extends Notification implements ShouldQueue

{

    use Queueable;



    public $quotation;



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

        $url = url(route('admin.sourcing-requests.show', $this->quotation->sourcingRequest->id));



                return (new MailMessage)



                    ->subject('Quotation Rejected for Sourcing Request: ' . $this->quotation->sourcingRequest->product_name)



                    ->greeting('Hello Admin,')



                    ->line('A quotation for Sourcing Request ' . $this->quotation->sourcingRequest->product_name . ' has been rejected by the client.')

            ->action('View Sourcing Request', $url)

            ->line('Please review the rejected quotation.');

    }



        public function toArray(object $notifiable): array



        {



            return [



                'quotation_id' => $this->quotation->id,



                'sourcing_request_id' => $this->quotation->sourcing_request_id,



                'title' => 'Quotation Rejected',



                'body' => "The quote for '{$this->quotation->sourcingRequest->product_name}' was rejected by the client.",



                'click_action' => route('admin.sourcing-requests.show', $this->quotation->sourcing_request_id),



                'type' => 'warning',



            ];



        }



    public function toFcm(object $notifiable): CloudMessage

    {

        $url = route('admin.sourcing-requests.show', $this->quotation->sourcing_request_id);



        return CloudMessage::withTarget('token', $notifiable->fcm_token)

            ->withNotification(FirebaseNotification::create(

                'Quotation Rejected',

                'Quotation for ' . $this->quotation->sourcingRequest->product_name . ' has been rejected.'

            ))

            ->withData([

                'click_action' => $url,

                'quotation_id' => (string) $this->quotation->id,

                'sourcing_request_id' => (string) $this->quotation->sourcing_request_id,

            ]);

    }

}
