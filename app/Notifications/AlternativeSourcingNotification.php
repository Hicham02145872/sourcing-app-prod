<?php

namespace App\Notifications;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlternativeSourcingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $maxExceptions = 3;

    public $backoff = [60, 300, 900];

    public function __construct(public Quotation $quotation)
    {
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject(__('Alternative Sourcing Location for Your Request'))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
            ->line(__('We have updated the sourcing location for your product ":product".', ['product' => $this->quotation->sourcingRequest->product_name]))
            ->line(__('Actual Sourcing Location: :location', ['location' => ucfirst($this->quotation->actual_sourcing_location)]));

        if ($this->quotation->sourcing_note) {
            $mailMessage->line(__('Note from our team: :note', ['note' => $this->quotation->sourcing_note]));
        }

        return $mailMessage->action(__('View Sourcing Request'), route('client.sourcing-requests.show', $this->quotation->sourcingRequest))
            ->line(__('Thank you for your business!'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'quotation_id' => $this->quotation->id,
            'sourcing_request_id' => $this->quotation->sourcing_request_id,
            'product_name' => $this->quotation->sourcingRequest->product_name,
            'actual_sourcing_location' => $this->quotation->actual_sourcing_location,
            'sourcing_note' => $this->quotation->sourcing_note,
            'title_key' => 'Alternative Sourcing Location',
            'body_key' => 'Alternative sourcing location (:location) chosen for :product',
            'body_params' => [
                'location' => ucfirst($this->quotation->actual_sourcing_location),
                'product' => $this->quotation->sourcingRequest->product_name,
            ],
            'type' => 'alternative_sourcing',
        ];
    }
}
