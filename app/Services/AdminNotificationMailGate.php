<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Notifications\Notification;

class AdminNotificationMailGate
{
    /** @var array<class-string, string>|null */
    protected static ?array $classToKeyCache = null;

    /**
     * @return list<string>
     */
    public static function allKeys(): array
    {
        return collect(config('admin_notifications.types', []))->pluck('key')->values()->all();
    }

    /**
     * @return list<string>
     */
    public static function defaultEnabledKeysForRole(string $role): array
    {
        if ($role === 'super_admin') {
            return array_values(array_unique(config('admin_notifications.super_admin_default_keys', [])));
        }

        return self::allKeys();
    }

    /**
     * Liste effective des types d'e-mails activés pour cet utilisateur (clés).
     *
     * @return list<string>
     */
    public function resolvedEnabledKeysForUser(User $user): array
    {
        $stored = $user->admin_mail_notification_keys;

        if ($stored === []) {
            return [];
        }

        if (is_array($stored) && $stored !== []) {
            $valid = self::allKeys();

            return array_values(array_intersect($stored, $valid));
        }

        return self::defaultEnabledKeysForRole((string) $user->role);
    }

    public function allowsMail(User $user, Notification $notification): bool
    {
        if (! $user->isAdmin()) {
            return true;
        }

        $key = $this->notificationKey($notification);
        if ($key === null) {
            return $user->role === 'admin';
        }

        return in_array($key, $this->resolvedEnabledKeysForUser($user), true);
    }

    public function notificationKey(Notification $notification): ?string
    {
        $class = get_class($notification);

        return self::classToKeyMap()[$class] ?? null;
    }

    /**
     * @return array<class-string, string>
     */
    protected static function classToKeyMap(): array
    {
        if (self::$classToKeyCache !== null) {
            return self::$classToKeyCache;
        }

        $map = [];
        foreach (config('admin_notifications.types', []) as $type) {
            $key = $type['key'] ?? null;
            foreach ($type['notification_classes'] ?? [] as $class) {
                if (is_string($class) && $key) {
                    $map[$class] = $key;
                }
            }
        }

        self::$classToKeyCache = $map;

        return $map;
    }
}
