<?php

namespace App\Notifications;

use App\Models\SourcingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class SourcingRequestCreated extends Notification implements ShouldQueue
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
        $channels = ['mail', 'database'];
        if ($notifiable->fcm_token) {
            $channels[] = 'fcm';
        }
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Sourcing Request Created: #' . $this->sourcingRequest->id)
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('A new sourcing request has been created by ' . $this->sourcingRequest->user->name . '.')
                    ->line('Product: ' . $this->sourcingRequest->product_name)
                    ->action('View Request', route('admin.sourcing-requests.show', $this->sourcingRequest))
                    ->line('Please review it at your earliest convenience.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'sourcing_request_id' => $this->sourcingRequest->id,
            'title' => 'New Sourcing Request: #' . $this->sourcingRequest->id,
            'body' => 'A new request for "' . $this->sourcingRequest->product_name . '" has been created.',
            'type' => 'sourcing_request',
        ];
    }

    /**
     * Get the FCM representation of the notification.
     */
    public function toFcm(object $notifiable)
    {
        $url = route('admin.sourcing-requests.show', $this->sourcingRequest->id);

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification(FirebaseNotification::create(
                'New Sourcing Request',
                'Request #' . $this->sourcingRequest->id . ' for ' . $this->sourcingRequest->product_name . ' has been created.'
            ))
            ->withData([
                'click_action' => $url,
                'sourcing_request_id' => (string) $this->sourcingRequest->id,
            ]);
    }
}
