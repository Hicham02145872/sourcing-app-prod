<?php

namespace App\Providers;

use App\Channels\FcmChannel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        \Illuminate\Support\Facades\Log::info('AppServiceProvider registered');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Notification::extend('fcm', function ($app) {
            // SUPPRIMEZ la ligne withApiKey() - elle n'existe pas!
            $factory = (new Factory)->withServiceAccount(config('firebase.projects.app.credentials'));
            
            $messaging = $factory->createMessaging();

            return new FcmChannel($messaging);
        });
    }
}