<?php

namespace App\Providers;

use App\Events\ProofOfPaymentUploadedEvent;
use App\Events\QuotationAccepted;
use App\Events\QuotationCreated;
use App\Events\QuotationRejected;
use App\Events\SourcingOrderStatusChanged;
use App\Events\SourcingRequestStatusChanged;
use App\Listeners\SendQuotationAcceptedNotification;
use App\Listeners\SendQuotationCreatedNotification;
use App\Listeners\SendQuotationRejectedNotification;
use App\Listeners\SendSourcingOrderStatusUpdatedNotification;
use App\Listeners\SendSourcingRequestStatusChangeNotification;
use App\Listeners\UpdateSourcingRequestStatusOnQuotationAccepted;
use App\Listeners\UpdateSourcingRequestStatusOnQuotationCreated;
use App\Listeners\UpdateSourcingRequestStatusOnQuotationRejected;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        \Illuminate\Auth\Events\Registered::class => [
            \Illuminate\Auth\Listeners\SendEmailVerificationNotification::class,
        ],
        QuotationCreated::class => [
            UpdateSourcingRequestStatusOnQuotationCreated::class,
            SendQuotationCreatedNotification::class,
        ],
        QuotationAccepted::class => [
            UpdateSourcingRequestStatusOnQuotationAccepted::class,
            SendQuotationAcceptedNotification::class,
            \App\Listeners\CopyEstimatesToSourcingOrder::class,
        ],
        QuotationRejected::class => [
            UpdateSourcingRequestStatusOnQuotationRejected::class,
            SendQuotationRejectedNotification::class,
        ],
        SourcingRequestStatusChanged::class => [
            SendSourcingRequestStatusChangeNotification::class,
        ],
        SourcingOrderStatusChanged::class => [
            SendSourcingOrderStatusUpdatedNotification::class,
            \App\Listeners\UpdateOrderStatusInGoogleSheet::class,
        ],
        ProofOfPaymentUploadedEvent::class => [
            \App\Listeners\SyncOrderToGoogleSheet::class,
        ],
        \Illuminate\Notifications\Events\NotificationFailed::class => [
            \App\Listeners\PruneInvalidFcmTokens::class,
        ],

    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}
