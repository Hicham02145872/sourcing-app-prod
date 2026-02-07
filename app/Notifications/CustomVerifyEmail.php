<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class CustomVerifyEmail extends VerifyEmailBase implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->onQueue('mail');
        $this->afterCommit();
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject(Lang::get('Welcome to :appName! Verify your email address', ['appName' => config('app.name')]))
            ->greeting(Lang::get('Hello :name!', ['name' => $notifiable->name]))
            ->line(Lang::get('We are thrilled to have you join **:appName**! You\'re just one step away from starting your sourcing journey.', ['appName' => config('app.name')]))
            ->line(Lang::get('Please click the button below to verify your email address and activate your account.'))
            ->action(Lang::get('Verify Email Address'), $verificationUrl)
            ->line(Lang::get('If you did not create an account, no further action is required.'))
            ->salutation(Lang::get('Best regards,')."\n".config('app.name').' Team');
    }
}
