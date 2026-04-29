<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

/**
 * Notification de test dédiée au Dev Dashboard (ne pas utiliser pour la logique métier).
 */
class DevPingNotification extends Notification
{
    use Queueable;

    /**
     * @param  array<int, string>  $viaChannels  ex: ['mail'], ['database'], ['fcm'], ou combinaison
     */
    public function __construct(
        public string $title,
        public string $body,
        public array $data = [],
        public array $viaChannels = ['database', 'fcm'],
    ) {
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        $out = [];
        foreach ($this->viaChannels as $channel) {
            if ($channel === 'fcm' && empty($notifiable->fcm_token)) {
                continue;
            }
            $out[] = $channel;
        }

        return array_values(array_unique($out));
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('[Dev] '.$this->title)
            ->line($this->body)
            ->line('Environnement : '.config('app.env').' ('.config('app.url').')');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'dev_ping',
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
            'sent_from' => 'dev_dashboard',
        ];
    }

    public function toFcm(object $notifiable): CloudMessage
    {
        $data = [
            'type' => 'dev_ping',
            'click_action' => 'NONE',
        ];
        foreach ($this->data as $key => $value) {
            $data[(string) $key] = is_scalar($value) ? (string) $value : json_encode($value);
        }

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification(FirebaseNotification::create($this->title, $this->body))
            ->withData($data);
    }
}
