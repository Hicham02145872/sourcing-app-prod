<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            return $setting?->value ?? $default;
        });
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting_{$key}");
    }

    public static function forget(string $key): void
    {
        static::where('key', $key)->delete();
        Cache::forget("setting_{$key}");
    }

    public static function isMaintenanceMode(): bool
    {
        return static::get('maintenance_mode') === '1';
    }

    public static function getMaintenanceMessage(): string
    {
        return static::get('maintenance_message', 'Site en maintenance. Nous serons de retour bientôt.');
    }

    public static function toggleMaintenance(bool $active, ?string $message = null): void
    {
        static::set('maintenance_mode', $active ? '1' : '0');
        if ($message !== null) {
            static::set('maintenance_message', $message);
        }
    }
}
