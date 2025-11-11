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

    // ✅ No retry (very important!)
    public $tries = 1;

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && $user->hasVerifiedEmail()) {
            Log::info('Email already verified for user, skipping verification email.', ['user_id' => $user->id]);
            return;
        }

        $lock = Cache::lock('verification_email_sent:' . $user->id, 120);

        try {
            if ($lock->get()) {
                $user->sendEmailVerificationNotification();
                Log::info('Email verification notification sent to user.', ['user_id' => $user->id]);
            }
        } catch (\Exception $e) {

            // ❌ Don't throw the exception (important!)
            Log::error('Failed to send verification email.', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

        } finally {
            optional($lock)->release();
        }
    }
}
