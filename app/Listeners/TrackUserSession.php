<?php

namespace App\Listeners;

use App\Models\UserSession;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class TrackUserSession
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        $sessionId = session()->getId();
        $ipAddress = request()->ip();
        $userAgent = request()->userAgent();

        // Mark all other sessions as not current
        UserSession::where('user_id', $user->id)
            ->update(['is_current' => false]);

        // Create or update current session
        UserSession::updateOrCreate(
            [
                'session_id' => $sessionId,
                'user_id' => $user->id,
            ],
            [
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'last_activity' => now(),
                'is_current' => true,
            ]
        );
    }
}
