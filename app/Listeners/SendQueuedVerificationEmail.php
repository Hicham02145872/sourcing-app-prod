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
        $lock = Cache::lock('verification_email_sent:' . $user->id, 60); // Lock for 60 seconds

        try {
            if ($lock->get()) {
                $user->sendEmailVerificationNotification();
                Log::info('Email verification notification sent to user.', ['user_id' => $user->id]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send email verification notification.', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            // Re-throw the exception to allow the job to be retried
            throw $e;
        } finally {
            optional($lock)->release();
        }
    }
}
