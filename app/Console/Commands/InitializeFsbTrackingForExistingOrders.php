<?php

namespace App\Console\Commands;

use App\Models\SourcingOrder;
use Illuminate\Console\Command;

class InitializeFsbTrackingForExistingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tracking:initialize-fsb-for-existing-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize fsb_tracking_created_at for existing paid orders that don\'t have it set';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Initializing FSB tracking for existing paid orders...');

        $orders = SourcingOrder::where('status', 'paid')
            ->whereNull('fsb_tracking_created_at')
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No orders found that need initialization.');
            return Command::SUCCESS;
        }

        $this->info("Found {$orders->count()} orders to initialize.");

        $bar = $this->output->createProgressBar($orders->count());
        $bar->start();

        foreach ($orders as $order) {
            // Use updated_at if it's more recent than created_at, otherwise use created_at
            $timestamp = $order->updated_at->isAfter($order->created_at) 
                ? $order->updated_at 
                : $order->created_at;

            $order->update([
                'fsb_tracking_created_at' => $timestamp,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully initialized FSB tracking for {$orders->count()} orders.");

        return Command::SUCCESS;
    }
}
