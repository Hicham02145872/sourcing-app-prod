<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

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
        return (new MailMessage)
            ->subject('[Dev] Laravel log error detected')
            ->view('emails.dev-laravel-log-error-alert', [
                'count' => count($this->errors),
                'errors' => array_slice($this->errors, 0, 10),
                'logSource' => $this->logSource,
                'environment' => config('app.env'),
                'dashboardUrl' => url('/admin/dev-dashboard'),
                'appName' => config('app.name', 'Laravel'),
            ]);
    }
}