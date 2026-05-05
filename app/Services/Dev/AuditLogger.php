<?php

namespace App\Services\Dev;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public static function log(string $action, ?string $targetType = null, ?int $targetId = null, array $payload = []): void
    {
        try {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'target_type' => $targetType,
                'target_id' => $targetId,
                'payload' => $payload ?: null,
                'ip' => request()?->ip(),
                'request_id' => request()?->attributes->get('request_id'),
            ]);
        } catch (\Throwable) {
            // Ne pas faire échouer une action critique si l'audit ne peut pas s'écrire
        }
    }
}
