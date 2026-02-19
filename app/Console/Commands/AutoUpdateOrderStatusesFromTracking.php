<?php

namespace App\Console\Commands;

use App\Models\SourcingOrder;
use App\Services\OrderStatus\AutoUpdateOrderStatusFromTracking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoUpdateOrderStatusesFromTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:auto-update-statuses 
                            {--limit= : Maximum number of orders to process}
                            {--dry-run : Run without actually updating statuses}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update order statuses based on real tracking events';

    protected AutoUpdateOrderStatusFromTracking $autoUpdateService;

    public function __construct(AutoUpdateOrderStatusFromTracking $autoUpdateService)
    {
        parent::__construct();
        $this->autoUpdateService = $autoUpdateService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Vérifier si l'auto-update est activé
        if (!config('app.auto_update_order_statuses_enabled', env('AUTO_UPDATE_ORDER_STATUSES_ENABLED', false))) {
            $this->warn('Auto-update is disabled. Set AUTO_UPDATE_ORDER_STATUSES_ENABLED=true in .env');
            return Command::FAILURE;
        }

        $this->info('🔄 Starting auto-update of order statuses from tracking...');
        $this->newLine();

        $startTime = microtime(true);
        $dryRun = $this->option('dry-run');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        if ($dryRun) {
            $this->warn('⚠️  DRY RUN MODE - No statuses will be updated');
            $this->newLine();
        }

        // Récupérer les commandes éligibles
        $orders = $this->getEligibleOrders($limit);

        $totalOrders = $orders->count();
        $this->info("📦 Found {$totalOrders} eligible orders");
        $this->newLine();

        if ($totalOrders === 0) {
            $this->info('✅ No orders to process');
            return Command::SUCCESS;
        }

        // Traiter les commandes
        if ($dryRun) {
            $this->info('🔍 DRY RUN - Would process the following orders:');
            foreach ($orders as $order) {
                $this->line("  - Order #{$order->id} ({$order->display_id}) - Status: {$order->status} - Tracking: {$order->tracking_number}");
            }
            $this->newLine();
            $this->info('✅ Dry run completed');
            return Command::SUCCESS;
        }

        $stats = $this->autoUpdateService->processBatch($orders);

        $executionTime = round(microtime(true) - $startTime, 2);

        // Afficher les résultats
        $this->newLine();
        $this->info('📊 Results:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Processed', $stats['processed']],
                ['Status Updated', $stats['updated']],
                ['Skipped', $stats['skipped']],
                ['Failed', $stats['failed']],
                ['Execution Time', "{$executionTime}s"],
            ]
        );

        // Logger les résultats
        Log::info('[AutoUpdateOrderStatuses] Command completed', [
            'stats' => $stats,
            'execution_time_seconds' => $executionTime,
            'limit' => $limit,
        ]);

        if ($stats['failed'] > 0) {
            $this->warn("⚠️  {$stats['failed']} orders failed to process. Check logs for details.");
            return Command::FAILURE;
        }

        $this->info('✅ Auto-update completed successfully');
        return Command::SUCCESS;
    }

    /**
     * Récupérer les commandes éligibles pour l'auto-update.
     */
    protected function getEligibleOrders(?int $limit = null)
    {
        $maxOrders = $limit ?? config('app.auto_update_max_orders_per_run', env('AUTO_UPDATE_MAX_ORDERS_PER_RUN', 100));
        $minIntervalMinutes = config('app.auto_update_min_interval_minutes', env('AUTO_UPDATE_MIN_INTERVAL_MINUTES', 60));

        $query = SourcingOrder::whereNotNull('tracking_number')
            ->where('tracking_number', '!=', '')
            ->whereNotNull('real_tracking_assigned_at') // ⚠️ CRITIQUE : Seulement les commandes avec tracking réel
            ->whereNotIn('status', ['on_hold', 'shipment_canceled', 'order_completed'])
            ->whereIn('status', [
                'shipment_preparing',
                'in_transit_china',
                'arrival_uae',
                'customs_clearance_uae',
                'in_transit_uae',
                'arrival_destination_country',
                'customs_clearance_destination_country',
                'out_for_delivery',
            ])
            ->orderBy('updated_at', 'asc') // Traiter les plus anciennes d'abord
            ->limit($maxOrders);

        // Optionnel : Ne traiter que les commandes qui n'ont pas été vérifiées récemment
        // (pour éviter de surcharger les APIs de tracking)
        if ($minIntervalMinutes > 0) {
            $query->where(function ($q) use ($minIntervalMinutes) {
                $q->whereNull('last_tracking_check_at')
                    ->orWhere('last_tracking_check_at', '<', now()->subMinutes($minIntervalMinutes));
            });
        }

        return $query->get();
    }
}
