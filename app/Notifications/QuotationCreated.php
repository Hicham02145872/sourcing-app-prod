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



                                        ->subject(__('New Quotation Received for Your Sourcing Request'))



                                        ->greeting(__('Hello,'))



                                        ->line(__('A new quotation of **:amount :currency** has been created for your sourcing request: **:productName**.', [



                                            'amount' => $this->quotation->amount,



                                            'currency' => $this->quotation->currency,



                                            'productName' => $this->quotation->sourcingRequest->product_name,



                                        ]))



                                        ->action(__('View Quotation'), $url)



                                        ->line(__('Please review the details and accept or reject the quotation.'))



                                        ->line(__('Thank you for using our service!'));

    }



        public function toArray(object $notifiable): array



        {



            return [



                                'title' => __('New Quotation Received'),



                                'body' => __('You\'ve received a new quote of :amount :currency for your request: \':productName\'.', [



                                    'amount' => $this->quotation->amount,



                                    'currency' => $this->quotation->currency,



                                    'productName' => $this->quotation->sourcingRequest->product_name,



                                ]),



                'sourcing_request_id' => $this->quotation->sourcing_request_id,



                'click_action' => route('client.sourcing-requests.show', $this->quotation->sourcingRequest->id),



            ];



        }



    public function toFcm(object $notifiable): CloudMessage

    {

        $url = route('client.sourcing-requests.show', $this->quotation->sourcing_request_id);



        return CloudMessage::withTarget('token', $notifiable->fcm_token)

                        ->withNotification(FirebaseNotification::create(

                            __('New Quotation Received'),

                            __('Quotation of :amount :currency for :productName', [

                                'amount' => $this->quotation->amount,

                                'currency' => $this->quotation->currency,

                                'productName' => $this->quotation->sourcingRequest->product_name,

                            ])

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
