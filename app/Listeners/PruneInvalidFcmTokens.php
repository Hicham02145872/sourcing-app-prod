<?php

namespace App\Listeners;

use Illuminate\Notifications\Events\NotificationFailed;

class PruneInvalidFcmTokens
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotificationFailed $event): void
    {
        if ($event->channel !== 'fcm') {
            return;
        }

        $exception = $event->data['exception'] ?? null;

        if (! $exception) {
            return;
        }

        $exceptionClass = get_class($exception);

        // List of exceptions that indicate the token is invalid/not registered
        $invalidTokenExceptions = [
            'Kreait\Firebase\Exception\Messaging\NotFound',
            'Kreait\Firebase\Exception\Messaging\RegistrationTokenNotRegistered',
            'Kreait\Firebase\Exception\Messaging\InvalidRegistrationToken',
            'Kreait\Firebase\Exception\Messaging\InvalidMessage', // Can happen if token is malformed
        ];

        $isInvalidToken = false;
        foreach ($invalidTokenExceptions as $invalidException) {
            if ($exception instanceof $invalidException) {
                $isInvalidToken = true;
                break;
            }
        }

        if ($isInvalidToken) {
            $notifiable = $event->notifiable;

            if ($notifiable && isset($notifiable->fcm_token)) {
                \Illuminate\Support\Facades\Log::warning("Pruning invalid FCM token for user {$notifiable->id}", [
                    'exception' => $exceptionClass,
                    'message' => $exception->getMessage(),
                ]);

                $notifiable->update(['fcm_token' => null]);
            }
        }
    }
}
