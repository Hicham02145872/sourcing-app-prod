<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(function () use ($user) {
            // Re-fetch the user and lock the row for the duration of the transaction
            $user = \App\Models\User::where('id', $user->id)->lockForUpdate()->first();

            // Double-check the email hasn't been verified and that we haven't sent the email yet
            if ($user->hasVerifiedEmail()) {
                Log::warning('User email already verified, skipping notification.', ['user_id' => $user->id]);
                return;
            }

            if (is_null($user->verification_email_sent_at)) {
                // Send the notification
                $user->sendEmailVerificationNotification();

                // Mark that we've sent it
                $user->verification_email_sent_at = now();
                $user->save();

                Log::info('Email verification notification sent to user.', ['user_id' => $user->id]);
            } else {
                Log::warning('Verification email already sent for user.', ['user_id' => $user->id]);
            }
        });
    }
}
