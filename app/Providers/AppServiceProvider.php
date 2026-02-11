<?php

namespace App\Providers;

use App\Channels\FcmChannel;
use App\Models\SocialMediaLink;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

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
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Mail\Events\MessageSent::class,
            \App\Listeners\MailSentListener::class
        );

        \App\Models\SourcingOrder::observe(\App\Observers\SourcingOrderObserver::class);
        \App\Models\Quotation::observe(\App\Observers\QuotationObserver::class);
        \App\Models\SourcingRequest::observe(\App\Observers\SourcingRequestObserver::class);

        Notification::extend('fcm', function ($app) {
            $credentials = config('firebase.projects.app.credentials');

            if (! $credentials || ! file_exists($credentials)) {
                \Illuminate\Support\Facades\Log::warning('FCM Notification skipped: Credentials file missing at '.$credentials);

                return new class
                {
                    public function send($notifiable, $notification)
                    {
                        // Logic to skip sending
                    }
                };
            }

            try {
                $factory = (new Factory)->withServiceAccount($credentials);
                $messaging = $factory->createMessaging();

                return new FcmChannel($messaging);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('FCM Driver Error: '.$e->getMessage());

                return new class
                {
                    public function send($notifiable, $notification)
                    {
                        // Logic to skip sending
                    }
                };
            }
        });

        View::composer(['components.layout.header', 'components.sidebar', 'client.dashboard', 'client.shipping-fees.index', 'livewire.client.shipping-fees-list'], function ($view) {
            try {
                $socialMediaLinks = SocialMediaLink::first();
                if (! $socialMediaLinks) {
                    // Create an empty object if no links are found, so the view doesn't crash
                    $socialMediaLinks = new SocialMediaLink;
                }
            } catch (\Exception $e) {
                // This can happen if the table doesn't exist yet (e.g., during initial migration)
                $socialMediaLinks = new SocialMediaLink;
            }
            $view->with('socialMediaLinks', $socialMediaLinks);
        });
        \Illuminate\Support\Facades\RateLimiter::for('google-sheets', function ($job) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(50);
        });
    }
}
