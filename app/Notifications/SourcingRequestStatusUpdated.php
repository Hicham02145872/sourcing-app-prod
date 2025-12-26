<?php

namespace App\Notifications;

use App\Models\SourcingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

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

     *

     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = ['database', 'mail'];

        if ($notifiable->fcm_token) {
            $channels[] = 'fcm';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.

     *

     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $statusLabel = $this->getStatusLabel($this->sourcingRequest->status);

        $url = url(route('client.sourcing-requests.show', $this->sourcingRequest->id));

        return (new MailMessage)
            ->subject(__('Your Sourcing Request #:requestId Status Update', ['requestId' => $this->sourcingRequest->id]))
            ->greeting(__('Hello,'))
            ->line(__('Good news! The status of your sourcing request for **:productName** has been updated.', ['productName' => $this->sourcingRequest->product_name]))
            ->line(__('New Status: **:status**', ['status' => $statusLabel]))
            ->action(__('View Your Request'), $url)
            ->line(__('Thank you for choosing our service!'));

    }

    /**
     * Get the array representation of the notification.

     *

     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {

        $statusLabel = $this->getStatusLabel($this->sourcingRequest->status);

        return [

            'title' => __('Sourcing Request Updated'),

            'body' => __('Your request for \':productName\' is now \':status\'.', [

                'productName' => $this->sourcingRequest->product_name,

                'status' => $statusLabel,

            ]),

            'click_action' => route('client.sourcing-requests.show', $this->sourcingRequest->id),

            'sourcing_request_id' => $this->sourcingRequest->id,

            'status' => $this->sourcingRequest->status,

        ];

    }

    /**
     * Get the FCM representation of the notification.

     *

     * @param  mixed  $notifiable
     * @return \Kreait\Firebase\Messaging\CloudMessage
     */
    public function toFcm($notifiable)
    {
        $statusLabel = $this->getStatusLabel($this->sourcingRequest->status);
        $url = route('client.sourcing-requests.show', $this->sourcingRequest->id);

        $imageUrl = $this->sourcingRequest->product_image ? asset('storage/'.$this->sourcingRequest->product_image) : null;

        $message = CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification(FirebaseNotification::create(
                __('Sourcing Request Update'),
                __('Your request :productName has been updated to status: :status.', [
                    'productName' => $this->sourcingRequest->product_name,
                    'status' => $statusLabel,
                ])
            ))
            ->withData([
                'click_action' => $url,
                'sourcing_request_id' => (string) $this->sourcingRequest->id,
            ]);

        if ($imageUrl) {
            $message = $message->withNotification(FirebaseNotification::create(
                __('Sourcing Request Update'),
                __('Your request :productName has been updated to status: :status.', [
                    'productName' => $this->sourcingRequest->product_name,
                    'status' => $statusLabel,
                ]),
                $imageUrl
            ));
        }

        return $message;
    }

    protected function getStatusLabel(string $status): string
    {

        $statusLabels = [

            'pending' => __('Pending'),

            'in_review' => __('In review'),

            'quoted' => __('Quoted'),

            'rejected' => __('Rejected'),

            'accepted' => __('Accepted'),

            'cancelled' => __('Cancelled'),

            'negotiating' => __('Negotiating'),

        ];

        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));

    }
}
