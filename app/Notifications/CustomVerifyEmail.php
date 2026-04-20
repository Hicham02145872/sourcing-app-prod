<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

/**
 * Envoyée en synchrone : la file d’attente (ShouldQueue) exige un worker
 * (`php artisan queue:work`) ou Redis/DB configurés — sinon aucun mail ne part.
 */
class CustomVerifyEmail extends VerifyEmailBase
{
    /**
     * Lien signé incluant /eng|/fr|/ar pour que la vérification ouvre la bonne langue.
     */
    protected function verificationUrl($notifiable): string
    {
        $locale = User::normalizeUrlLocale($notifiable->preferred_locale ?? null);

        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes((int) config('auth.verification.expire', 60)),
            [
                'locale' => $locale,
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        $urlLocale = User::normalizeUrlLocale($notifiable->preferred_locale ?? null);
        $appLocale = $urlLocale === 'eng' ? 'en' : $urlLocale;

        $previousLocale = App::getLocale();
        try {
            App::setLocale($appLocale);

            return (new MailMessage)
                ->subject(Lang::get('Welcome to :appName! Verify your email address', ['appName' => config('app.name')]))
                ->greeting(Lang::get('Hello :name!', ['name' => $notifiable->name]))
                ->line(Lang::get('We are thrilled to have you join **:appName**! You\'re just one step away from starting your sourcing journey.', ['appName' => config('app.name')]))
                ->line(Lang::get('Please click the button below to verify your email address and activate your account.'))
                ->action(Lang::get('Verify Email Address'), $verificationUrl)
                ->line(Lang::get('If you did not create an account, no further action is required.'))
                ->salutation(Lang::get('Best regards,')."\n".config('app.name').' Team');
        } finally {
            App::setLocale($previousLocale);
        }
    }
}
