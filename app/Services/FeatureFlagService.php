<?php

namespace App\Services;

use App\Models\FeatureFlag;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class FeatureFlagService
{
    /**
     * Get the status of a feature flag for a specific user.
     *
     * @return string 'visible', 'hidden', 'coming_soon'
     */
    public function getFlagStatus(string $key, ?User $user = null): string
    {
        $flag = Cache::remember("feature_flag_{$key}", now()->addHours(1), function () use ($key) {
            return FeatureFlag::where('key', $key)->first();
        });

        if (! $flag) {
            return 'visible'; // Default to visible if not defined
        }

        // If specific roles are defined, check if user has one of them
        if (! empty($flag->roles)) {
            if (! $user) {
                return 'hidden';
            }

            if (! $user->isSuperAdmin() && ! in_array($user->role, $flag->roles)) {
                return 'hidden';
            }
        }

        return $flag->status;
    }

    /**
     * Check if a feature should be visible (either fully visible or in coming_soon state).
     */
    public function isEnabled(string $key, ?User $user = null): bool
    {
        $status = $this->getFlagStatus($key, $user);

        return $status === 'visible' || $status === 'coming_soon';
    }

    /**
     * Check if a feature is fully visible.
     */
    public function isVisible(string $key, ?User $user = null): bool
    {
        return $this->getFlagStatus($key, $user) === 'visible';
    }

    /**
     * Check if a feature is in coming_soon state.
     */
    public function isComingSoon(string $key, ?User $user = null): bool
    {
        return $this->getFlagStatus($key, $user) === 'coming_soon';
    }

    /**
     * Clear the cache for a specific flag.
     */
    public function clearCache(string $key): void
    {
        Cache::forget("feature_flag_{$key}");
    }
}
