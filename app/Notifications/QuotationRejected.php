<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Quotation;

class QuotationRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public $quotation;

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

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('admin.sourcing-requests.show', $this->quotation->sourcingRequest->id));

        return (new MailMessage)
            ->subject('Quotation Rejected for Sourcing Request #' . $this->quotation->sourcingRequest->id)
            ->greeting('Hello Admin,')
            ->line('A quotation for Sourcing Request #' . $this->quotation->sourcingRequest->id . ' has been rejected by the client.')
            ->action('View Sourcing Request', $url)
            ->line('Please review the rejected quotation.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'quotation_id' => $this->quotation->id,
            'sourcing_request_id' => $this->quotation->sourcing_request_id,
            'title' => 'Quotation Rejected',
            'body' => 'The quotation for sourcing request #' . $this->quotation->sourcing_request_id . ' has been rejected by the client.',
            'click_action' => route('admin.sourcing-requests.show', $this->quotation->sourcing_request_id),
            'type' => 'warning',
        ];
    }
}