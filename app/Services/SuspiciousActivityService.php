<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SuspiciousActivityService
{
    /**
     * Check for suspicious login activity (new IP address).
     */
    public function checkSuspiciousLogin(User $user, string $ipAddress): bool
    {
        // Get recent login IPs for this user (last 30 days)
        $recentIPs = DB::table('user_sessions')
            ->where('user_id', $user->id)
            ->where('last_activity', '>=', now()->subDays(30))
            ->distinct()
            ->pluck('ip_address')
            ->toArray();

        // If this is a new IP address, it's suspicious
        if (!in_array($ipAddress, $recentIPs)) {
            $this->sendSuspiciousActivityAlert($user, 'login', [
                'ip' => $ipAddress,
                'message' => 'Connexion depuis une nouvelle adresse IP',
            ]);

            return true;
        }

        return false;
    }

    /**
     * Check for suspicious password change activity.
     */
    public function checkSuspiciousPasswordChange(User $user, string $ipAddress): bool
    {
        // Get recent activity IPs for this user
        $recentIPs = DB::table('user_sessions')
            ->where('user_id', $user->id)
            ->where('last_activity', '>=', now()->subDays(7))
            ->distinct()
            ->pluck('ip_address')
            ->toArray();

        // If password changed from a new IP, it's suspicious
        if (!in_array($ipAddress, $recentIPs)) {
            $this->sendSuspiciousActivityAlert($user, 'password_change', [
                'ip' => $ipAddress,
                'message' => 'Changement de mot de passe depuis une nouvelle adresse IP',
            ]);

            return true;
        }

        return false;
    }

    /**
     * Send suspicious activity alert to user.
     */
    protected function sendSuspiciousActivityAlert(User $user, string $activityType, array $data): void
    {
        Log::channel('auth')->warning('Suspicious activity detected', [
            'user_id' => $user->id,
            'email' => $user->email,
            'activity_type' => $activityType,
            'data' => $data,
            'timestamp' => now()->toIso8601String(),
        ]);

        // Send email notification to user
        try {
            Mail::send('emails.suspicious-activity', [
                'user' => $user,
                'activityType' => $activityType,
                'data' => $data,
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Alerte de sécurité - Activité suspecte détectée');
            });
        } catch (\Exception $e) {
            Log::channel('auth')->error('Failed to send suspicious activity email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
