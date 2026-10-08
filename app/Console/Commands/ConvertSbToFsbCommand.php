<?php

namespace App\Console\Commands;

use App\Services\SharedIdService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ConvertSbToFsbCommand extends Command
{
    protected $signature = 'fsb:renumber
                            {--dry-run : Preview without writing}
                            {--export= : Path to write CSV mapping (default: storage/exports/fsb-renumber-map.csv)}';

    protected $description = 'Re-number all NULL and SB shared_ids to fresh FSB sequential numbers (requests + orders)';

    public function handle(SharedIdService $sharedIdService): int
    {
        $dryRun = $this->option('dry-run');
        $exportPath = $this->option('export') ?? storage_path('exports/fsb-renumber-map.csv');
        $this->info($dryRun ? '🔍 DRY-RUN mode — no writes' : '⚠️  LIVE mode — committing changes');
        $this->line('');

        // 1. Fetch candidates (NULL or SB) ordered by id for deterministic sequence
        $requests = \App\Models\SourcingRequest::query()
            ->where(fn ($q) => $q->whereNull('shared_id')->orWhere('shared_id', 'like', 'SB%'))
            ->orderBy('id')
            ->get();

        $this->info('Found ' . $requests->count() . ' requests to renumber (NULL + SB)');
        $this->line('');

        $mapping = [];

        // 2. Inside one transaction, generate sequential numbers
        DB::beginTransaction();

        try {
            $generated = 0;

            foreach ($requests as $request) {
                $oldSharedId = $request->shared_id;
                $newSharedId = $sharedIdService->generate();

                // Update request
                if (!$dryRun) {
                    \App\Models\SourcingRequest::withoutTimestamps(function () use ($request, $newSharedId) {
                        DB::table('sourcing_requests')
                            ->where('id', $request->id)
                            ->update(['shared_id' => $newSharedId]);
                    });
                }

                // Update ALL orders linked to this request (they inherit shared_id)
                $orderCount = \App\Models\SourcingOrder::where('sourcing_request_id', $request->id)->count();

                if (!$dryRun) {
                    DB::table('sourcing_orders')
                        ->where('sourcing_request_id', $request->id)
                        ->update(['shared_id' => $newSharedId]);
                }

                $generated++;

                $mapping[] = [
                    'request_id' => $request->id,
                    'old_shared_id' => $oldSharedId ?? 'NULL',
                    'new_shared_id' => $newSharedId,
                    'orders_updated' => $orderCount,
                ];

                if ($this->getOutput()->isVerbose()) {
                    $this->line(sprintf(
                        '  SR#%-4d  %-10s → %-10s  (+%d order%s)',
                        $request->id,
                        $oldSharedId ?? 'NULL',
                        $newSharedId,
                        $orderCount,
                        $orderCount === 1 ? '' : 's'
                    ));
                }
            }

            if ($dryRun) {
                DB::rollBack();
                $this->info("\n✅ Rolled back (dry-run)");
            } else {
                DB::commit();
                $this->info("\n✅ Committed {$generated} requests + linked orders");
            }

            // 3. Summary stats
            $this->line('');
            $this->line('── SUMMARY ──');
            $this->line("Requests renumbered : {$generated}");
            $this->line("Orders updated      : " . array_sum(array_column($mapping, 'orders_updated')));
            if (!empty($mapping)) {
                $first = $mapping[0]['new_shared_id'];
                $last  = $mapping[array_key_last($mapping)]['new_shared_id'];
                $this->line("FSB range           : {$first} → {$last}");
            }
            $this->line("Sequence now       : " . $sharedIdService->currentCounter());

            // 4. Export mapping CSV
            if (!empty($mapping)) {
                $dir = dirname($exportPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                $fp = fopen($exportPath, 'w');
                fputcsv($fp, ['request_id', 'old_shared_id', 'new_shared_id', 'orders_updated']);
                foreach ($mapping as $row) {
                    fputcsv($fp, $row);
                }
                fclose($fp);
                $this->info("📝 Mapping CSV written to {$exportPath}");
            }

            // 5. Purge tracking caches
            if (!$dryRun) {
                $oldFsbNumbers = collect($mapping)
                    ->filter(fn ($m) => $m['old_shared_id'] !== 'NULL')
                    ->pluck('old_shared_id')
                    ->all();

                $newFsbNumbers = collect($mapping)->pluck('new_shared_id')->all();

                $allFsbToFlush = array_unique(array_merge($oldFsbNumbers, $newFsbNumbers));

                foreach ($allFsbToFlush as $fsb) {
                    \Illuminate\Support\Facades\Cache::forget("tracking:{$fsb}");
                }

                $this->info("🗑️  Flushed " . count($allFsbToFlush) . " tracking cache entries");
            }

            return self::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Error — rolled back: " . $e->getMessage());
            return self::FAILURE;
        }
    }
}
