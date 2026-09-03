<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurgeSpamAccounts extends Command
{
    protected $signature = 'auth:purge-spam {--hours=48 : Delete unverified accounts older than this many hours}';

    protected $description = 'Remove unverified spam accounts (email_verified_at IS NULL) older than N hours';

    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        $cutoff = now()->subHours($hours);

        $deleted = User::whereNull('email_verified_at')
            ->where('created_at', '<', $cutoff)
            ->delete();

        $message = "Purged {$deleted} unverified accounts older than {$hours}h.";

        $this->info($message);
        Log::info("[auth:purge-spam] {$message}");

        return Command::SUCCESS;
    }
}
