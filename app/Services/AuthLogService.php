<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthLogService
{
    /**
     * Log a login attempt.
     */
    public function logLogin(?User $user, bool $success, ?string $email = null, ?string $ip = null): void
    {
        Log::channel('auth')->info('Login attempt', [
            'user_id' => $user?->id,
            'email' => $email ?? request('email'),
            'success' => $success,
            'ip' => $ip ?? request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Log a password change.
     */
    public function logPasswordChange(User $user): void
    {
        Log::channel('auth')->info('Password changed', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Log an email change.
     */
    public function logEmailChange(User $user, string $oldEmail): void
    {
        Log::channel('auth')->warning('Email changed', [
            'user_id' => $user->id,
            'old_email' => $oldEmail,
            'new_email' => $user->email,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Log a registration.
     */
    public function logRegistration(User $user): void
    {
        Log::channel('auth')->info('User registered', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Log a password reset request.
     */
    public function logPasswordResetRequest(string $email): void
    {
        Log::channel('auth')->info('Password reset requested', [
            'email' => $email,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Log a password reset completion.
     */
    public function logPasswordReset(User $user): void
    {
        Log::channel('auth')->info('Password reset completed', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Log a logout.
     */
    public function logLogout(User $user): void
    {
        Log::channel('auth')->info('User logged out', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
