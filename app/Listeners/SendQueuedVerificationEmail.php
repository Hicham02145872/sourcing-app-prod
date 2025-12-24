<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;

class SendQueuedVerificationEmail implements ShouldQueue
{
    use InteractsWithQueue;


    public $tries = 3;
    public $afterCommit = true;

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $user = $event->user;
        $listener = class_basename(static::class);
        $shouldSendEmail = false;

        try {
            Log::info("🔥 {$listener} STARTED", [
                'user_id' => $user->id,
                'email' => $user->email,
                'job_id' => $this->job?->getJobId(),
                'attempt' => $this->attempts(),
            ]);

            if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && $user->hasVerifiedEmail()) {
                Log::info("✅ {$listener}: Email already verified, skipping", ['user_id' => $user->id]);
                return;
            }

            DB::transaction(function () use (&$user, $listener, &$shouldSendEmail) {
                $user = \App\Models\User::where('id', $user->id)->lockForUpdate()->first();

                if ($user->hasVerifiedEmail()) {
                    Log::warning("⚠️ {$listener}: User email already verified (double check)", ['user_id' => $user->id]);
                    return;
                }

                if (is_null($user->verification_email_sent_at)) {
                    $user->verification_email_sent_at = now();
                    $user->save();
                    $shouldSendEmail = true;
                } else {
                    Log::warning("⚠️ {$listener}: Verification email already sent", ['user_id' => $user->id]);
                }
            });

            if ($shouldSendEmail) {
                Log::info("📤 {$listener}: Sending email verification...", ['user_id' => $user->id]);
                $user->sendEmailVerificationNotification();
                Log::info("✅ {$listener}: Email sent successfully", ['user_id' => $user->id]);
            }

            Log::info("🏁 {$listener} COMPLETED", [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        } catch (Throwable $e) {
            Log::error("❌ {$listener} FAILED", [
                'user_id' => $user->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e; // laisser Laravel re-essayer si nécessaire
        }
    }
}
