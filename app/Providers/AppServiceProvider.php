<?php

namespace App\Providers;

use App\Channels\FcmChannel;
use App\Models\SocialMediaLink;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
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
        $this->app->singleton(\App\Services\FeatureFlagService::class, function ($app) {
            return new \App\Services\FeatureFlagService;
        });

        $this->app->afterResolving(\Illuminate\Console\Command::class, function ($command, $app) {
            $command->setLaravel($app);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Queue/CLI contexts have no HTTP request, so localized client routes
        // used in notifications can miss the required {locale} parameter.
        // Keep a safe default; web middleware can still override per request.
        URL::defaults(['locale' => 'eng']);

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

        // Share the current admin's SLA overdue stats with the sidebar so the
        // SLA entry/button can be rendered on every admin page, not just the
        // dashboard. Super admins are exempt and get the section hidden.
        View::composer('components.sidebar', function ($view) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $slaSidebarEnabled = false;

            $slaSidebarRequestCount = 0;
            $slaSidebarOrderCount = 0;
            $slaSidebarRequestByStatus = [];
            $slaSidebarOrderByStatus = [];
            $slaSidebarProcessUrl = null;
            $slaSidebarProcessHint = null;

            if ($user && ! $user->isSuperAdmin()
                && app(\App\Services\FeatureFlagService::class)->isEnabled('sla_deadlines_autolock', $user)) {
                $stats = app(\App\Services\SlaOverdueService::class)->statsForUser($user);
                $slaSidebarEnabled = true;
                $slaSidebarRequestCount = $stats['requestCount'];
                $slaSidebarOrderCount = $stats['orderCount'];
                $slaSidebarRequestByStatus = $stats['requestByStatus'];
                $slaSidebarOrderByStatus = $stats['orderByStatus'];
                // Adaptable shortcut: point at the most urgent overdue item
                // (quotation page for requests, order page for orders) with a
                // hint matching the item type instead of always "folder(s)".
                $slaSidebarProcessUrl = app(\App\Services\SlaOverdueService::class)->mostUrgentTargetUrl($stats);
                if ($slaSidebarRequestCount > 0 && $slaSidebarOrderCount > 0) {
                    $slaSidebarProcessHint = __('sla.sidebar_hint_both', ['requests' => $slaSidebarRequestCount, 'orders' => $slaSidebarOrderCount]);
                } elseif ($slaSidebarOrderCount > 0) {
                    $slaSidebarProcessHint = __('sla.sidebar_hint_orders', ['count' => $slaSidebarOrderCount]);
                } else {
                    $slaSidebarProcessHint = __('sla.sidebar_hint', ['count' => $slaSidebarRequestCount]);
                }
            }

            $view->with('slaSidebarEnabled', $slaSidebarEnabled)
                ->with('slaSidebarRequestCount', $slaSidebarRequestCount)
                ->with('slaSidebarOrderCount', $slaSidebarOrderCount)
                ->with('slaSidebarRequestByStatus', $slaSidebarRequestByStatus)
                ->with('slaSidebarOrderByStatus', $slaSidebarOrderByStatus)
                ->with('slaSidebarProcessUrl', $slaSidebarProcessUrl)
                ->with('slaSidebarProcessHint', $slaSidebarProcessHint);
        });

        // Share the persistent workflow banners (SLA overdue + in-review
        // limit) with the other admin pages an admin lands on to process
        // folders, so the dashboard banner also shows there.
        View::composer([
            'admin.sourcing-requests.index',
            'admin.quotations.create',
            'admin.quotations.edit',
            'admin.sourcing-orders.show',
        ], function ($view) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if (! $user) {
                return;
            }
            foreach (app(\App\Services\WorkflowAlertService::class)->bannerData($user) as $key => $value) {
                $view->with($key, $value);
            }
        });
        \Illuminate\Support\Facades\RateLimiter::for('google-sheets', function ($job) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(50);
        });

        // Feature Flag Blade Directives
        \Illuminate\Support\Facades\Blade::if('feature', function ($key) {
            return app(\App\Services\FeatureFlagService::class)->isEnabled($key, auth()->user());
        });

        // Même logique que @feature : plus de condition stricte (visible + coming_soon affichés)
        \Illuminate\Support\Facades\Blade::if('featureVisible', function ($key) {
            return app(\App\Services\FeatureFlagService::class)->isEnabled($key, auth()->user());
        });

        \Illuminate\Support\Facades\Blade::if('featureComingSoon', function ($key) {
            return app(\App\Services\FeatureFlagService::class)->isComingSoon($key, auth()->user());
        });

        // Erreurs dans les actions Livewire : journal + toast (évite un 500 brut sur requête AJAX).
        // L’événement interne Livewire s’appelle « exception » (voir Livewire\Wrapped).
        \Livewire\Livewire::listen('exception', function ($component, \Throwable $exception, callable $stopPropagation): void {
            \Illuminate\Support\Facades\Log::error('Livewire component exception', [
                'component' => is_object($component) ? $component::class : null,
                'message' => $exception->getMessage(),
                'request_id' => request()->attributes->get('request_id'),
            ]);

            try {
                if (is_object($component) && method_exists($component, 'dispatch')) {
                    $component->dispatch(
                        'show-error-toast',
                        message: __('An unexpected error occurred. Please retry.')
                    );
                }
            } catch (\Throwable) {
                // Ignorer si le composant ne peut pas émettre d’événement
            }

            $stopPropagation();
        });
    }
}
