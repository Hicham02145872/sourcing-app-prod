<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendQueuedVerificationEmail implements ShouldQueue
{
    use InteractsWithQueue;

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
    public function handle(Registered $event): void
    {
        $user = $event->user;
        $lockKey = 'verification_email_sent:' . $user->id;

        if (Cache::has($lockKey)) {
            Log::info('Verification email already sent recently for user.', ['user_id' => $user->id]);
            return;
        }

        Cache::put($lockKey, true, 60); // Lock for 60 seconds

        try {
            $user->sendEmailVerificationNotification();
            Log::info('Email verification notification sent to user.', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Cache::forget($lockKey); // Release lock on failure
            Log::error('Failed to send email verification notification.', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            // Optionally, re-throw the exception if you want the job to be retried
            // throw $e;
        }
    }
}
