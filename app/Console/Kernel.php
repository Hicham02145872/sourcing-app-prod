<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping();
        
        // Auto-update des statuts de commande basés sur le tracking
        // Voir docs/CRON_JOB_SETUP_WALKTHROUGH.md pour la configuration complète
        if (config('app.auto_update_order_statuses_enabled', env('AUTO_UPDATE_ORDER_STATUSES_ENABLED', false))) {
            $schedule->command('orders:auto-update-statuses')
                ->everyFourHours() // Toutes les 4 heures
                ->withoutOverlapping() // Éviter les exécutions simultanées
                ->onOneServer() // Si plusieurs serveurs, exécuter sur un seul
                ->appendOutputTo(storage_path('logs/auto-update-statuses.log'))
                ->emailOutputOnFailure(config('mail.admin_email', env('MAIL_ADMIN_EMAIL')));
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
