<?php

namespace App\Console\Commands;

use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Services\SharedIdService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InstallSharedIdCommand extends Command
{
    protected $signature = 'shared-id:install {--backfill : Assign SB ids to existing records without shared_id}';

    protected $description = 'Install SB shared_id schema (migrations) and optionally backfill existing records';

    public function handle(SharedIdService $sharedIdService): int
    {
        $migrations = [
            '2026_05_20_000001_create_sb_id_sequence_table.php',
            '2026_05_20_000002_add_shared_id_to_sourcing_requests_table.php',
            '2026_05_20_000003_add_shared_id_to_sourcing_orders_table.php',
        ];

        foreach ($migrations as $file) {
            $this->runMigrationFile($file);
        }

        if ($this->option('backfill')) {
            $this->backfill($sharedIdService);
        }

        $this->info('SB shared_id install complete.');
        $this->line('New sourcing requests will receive IDs like SB00005, SB00010, ...');

        return self::SUCCESS;
    }

    protected function runMigrationFile(string $file): void
    {
        $name = pathinfo($file, PATHINFO_FILENAME);
        $path = database_path('migrations/'.$file);

        if (! file_exists($path)) {
            $this->warn("Migration file missing: {$file}");

            return;
        }

        if (DB::table('migrations')->where('migration', $name)->exists()) {
            $this->line("Already applied: {$name}");

            return;
        }

        $migration = require $path;
        $migration->up();
        DB::table('migrations')->insert([
            'migration' => $name,
            'batch' => (int) DB::table('migrations')->max('batch') + 1,
        ]);
        $this->info("Applied: {$name}");
    }

    protected function backfill(SharedIdService $sharedIdService): void
    {
        if (! Schema::hasColumn('sourcing_requests', 'shared_id')) {
            $this->error('Column sourcing_requests.shared_id is missing. Run install first.');

            return;
        }

        $requests = SourcingRequest::whereNull('shared_id')->orderBy('id')->get();
        $this->info("Backfilling {$requests->count()} sourcing request(s)...");

        foreach ($requests as $sr) {
            $sr->shared_id = $sharedIdService->generate();
            $sr->saveQuietly();
            $this->line("  SR #{$sr->id} -> {$sr->shared_id}");
        }

        $orders = SourcingOrder::whereNull('shared_id')
            ->whereNotNull('sourcing_request_id')
            ->orderBy('id')
            ->get();

        $this->info("Backfilling {$orders->count()} sourcing order(s)...");

        foreach ($orders as $order) {
            $sharedId = SourcingRequest::whereKey($order->sourcing_request_id)->value('shared_id');
            if ($sharedId) {
                $order->shared_id = $sharedId;
                $order->saveQuietly();
                $this->line("  Order #{$order->id} -> {$sharedId}");
            }
        }
    }
}
