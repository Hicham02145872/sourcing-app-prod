<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientRegisteredForAdmins extends Notification
{
    use Queueable;

    public function __construct(public User $client)
    {
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        $channels = ['database', 'mail'];
        if ($notifiable->fcm_token) {
            $channels[] = 'fcm';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New client registration: :name', ['name' => $this->client->name]))
            ->greeting(__('Hello').' '.$notifiable->name.',')
            ->line(__('A new client has registered on the platform.'))
            ->line(__('Name: :name', ['name' => $this->client->name]))
            ->line(__('Email: :email', ['email' => $this->client->email]))
            ->action(__('View clients'), route('admin.users.index'));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => __('New client registration'),
            'body' => $this->client->name.' ('.$this->client->email.')',
            'type' => 'info',
            'registered_user_id' => $this->client->id,
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => __('New client'),
            'body' => $this->client->name,
            'data' => [
                'registered_user_id' => (string) $this->client->id,
                'click_action' => 'VIEW_CLIENTS',
                'url' => route('admin.users.index'),
            ],
        ];
    }
}
