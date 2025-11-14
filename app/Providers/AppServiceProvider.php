<?php

namespace App\Providers;

use App\Channels\FcmChannel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;

use Illuminate\Support\Facades\View;
use App\Models\SocialMediaLink;
use Illuminate\Support\Facades\DB;

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

        View::composer('components.layout.header', function ($view) {
            try {
                $socialMediaLinks = SocialMediaLink::first();
                if (!$socialMediaLinks) {
                    // Create an empty object if no links are found, so the view doesn't crash
                    $socialMediaLinks = new SocialMediaLink();
                }
            } catch (\Exception $e) {
                // This can happen if the table doesn't exist yet (e.g., during initial migration)
                $socialMediaLinks = new SocialMediaLink();
            }
            $view->with('socialMediaLinks', $socialMediaLinks);
        });
    }
}