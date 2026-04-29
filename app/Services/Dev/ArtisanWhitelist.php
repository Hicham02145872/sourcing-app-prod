<?php

namespace App\Services\Dev;

class ArtisanWhitelist
{
    /**
     * Commandes autorisées depuis le Dev Dashboard (sans arguments libres dangereux).
     * Format: nom de commande seulement; les options sont passées séparément si besoin.
     */
    public const ALLOWED_COMMANDS = [
        'optimize',
        'config:cache',
        'config:clear',
        'route:cache',
        'route:clear',
        'view:cache',
        'view:clear',
        'cache:clear',
        'queue:restart',
        'queue:retry',
        'optimize:clear',
        'migrate',
        'migrate:status',
        'horizon:status',
        'storage:link',
        'db:show',
    ];

    public function normalizeInput(string $raw): string
    {
        return trim(preg_replace('/\s+/', ' ', $raw) ?? '');
    }

    public function isAllowed(string $commandLine): bool
    {
        $line = $this->normalizeInput($commandLine);
        if ($line === '') {
            return false;
        }

        // Rejeter ; | & redirections
        if (preg_match('/[;&|`$<>]/', $line)) {
            return false;
        }

        $parts = explode(' ', $line);
        $base = $parts[0];

        if (! in_array($base, self::ALLOWED_COMMANDS, true)) {
            return false;
        }

        if ($base === 'migrate') {
            // Autoriser uniquement migrate --force ou migrate sans option (état)
            foreach (array_slice($parts, 1) as $arg) {
                if ($arg === '--force' || $arg === '--no-interaction') {
                    continue;
                }
                if (str_starts_with($arg, '--')) {
                    return false;
                }

                return false;
            }
        }

        if ($base === 'queue:retry') {
            // Une seule cible : ID numérique (failed_jobs.id)
            if (count($parts) !== 2 || ! ctype_digit((string) $parts[1])) {
                return false;
            }
        }

        return true;
    }

    public function assertAllowed(string $commandLine): void
    {
        if (! $this->isAllowed($commandLine)) {
            throw new \InvalidArgumentException('Commande Artisan non autorisée depuis ce panneau.');
        }
    }
}
