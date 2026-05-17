<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DevLaravelLogErrorAlert extends Notification
{
    use Queueable;

    /**
     * @param  array<int, array<string, mixed>>  $errors
     */
    public function __construct(
        public array $errors,
        public string $logSource = 'laravel'
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $count = count($this->errors);
        $recentErrors = array_slice($this->errors, 0, 5);

        $mail = (new MailMessage)
            ->subject('[Dev] Laravel log error detected')
            ->greeting('Hello,')
            ->line('An error was detected in the '.$this->logSource.' logs.')
            ->line('Environment: '.config('app.env'))
            ->line('Error count: '.$count);

        foreach ($recentErrors as $error) {
            $mail->line($error['text'] ?? 'Unknown error');
        }

        $mail->line('Please review the Dev Dashboard for more details.');

        if (config('app.url')) {
            $mail->action('Open Dev Dashboard', url('/admin/dev-dashboard'));
        }

        return $mail;
    }
}