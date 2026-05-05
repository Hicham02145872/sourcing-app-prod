<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientAccountCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $plainPassword)
    {
        //
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
        return (new MailMessage)
            ->subject(__('Your Account Credentials'))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
            ->line(__('An account has been created for you on :app_name.', ['app_name' => config('app.name')]))
            ->line(__('You can log in using the following credentials:'))
            ->line(__('**Email:** :email', ['email' => $notifiable->email]))
            ->line(__('**Password:** :password', ['password' => $this->plainPassword]))
            ->action(__('Login to Your Account'), route('login'))
            ->line(__('For security reasons, we recommend you change your password after your first login.'))
            ->line(__('Thank you for using our application!'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => __('Account Created'),
            'message' => __('Your account has been created successfully.'),
            'type' => 'info',
        ];
    }
}
