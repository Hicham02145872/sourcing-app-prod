<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class TrackingClearCacheCommand extends Command
{
    protected $signature = 'tracking:clear-cache 
                            {number? : Numéro de suivi à vider du cache (ex: 1Z14V4W16890506495 ou FSB000042)}';

    protected $description = 'Vider le cache de suivi pour un numéro donné';

    public function handle(): int
    {
        $number = trim((string) ($this->argument('number') ?? ''));
        if ($number === '') {
            $this->error('Indiquez le numéro de suivi à vider du cache.');
            $this->line('Exemple: php artisan tracking:clear-cache 1Z14V4W16890506495');
            $this->line('         php artisan tracking:clear-cache FSB000042');
            return self::FAILURE;
        }
        $cacheKey = "tracking:{$number}";
        $pendingKey = "tracking_pending:{$number}";

        Cache::forget($cacheKey);
        Cache::forget($pendingKey);

        $this->info("Cache vidé pour le numéro: {$number}");

        return self::SUCCESS;
    }
}
