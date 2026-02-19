<?php

namespace App\Providers;

use App\Events\ProofOfPaymentUploadedEvent;
use App\Events\QuotationAccepted;
use App\Events\QuotationCreated;
use App\Events\QuotationRejected;
use App\Events\RefundRequestUpdated;
use App\Events\SourcingOrderStatusChanged;
use App\Events\SourcingRequestStatusChanged;
use App\Listeners\SendQuotationAcceptedNotification;
use App\Listeners\SendQuotationCreatedNotification;
use App\Listeners\SendQuotationRejectedNotification;
use App\Listeners\InitializeFsbTracking;
use App\Listeners\SendRefundStatusNotification;
use App\Listeners\SendSourcingOrderStatusUpdatedNotification;
use App\Listeners\SendSourcingRequestStatusChangeNotification;
use App\Listeners\UpdateSourcingRequestStatusOnQuotationAccepted;
use App\Listeners\UpdateSourcingRequestStatusOnQuotationCreated;
use App\Listeners\UpdateSourcingRequestStatusOnQuotationRejected;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // \Illuminate\Auth\Events\Registered::class => [
        //     \Illuminate\Auth\Listeners\SendEmailVerificationNotification::class,
        // ],
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
            InitializeFsbTracking::class,
            SendSourcingOrderStatusUpdatedNotification::class,
            \App\Listeners\SyncOrderToSheet::class,
        ],
        ProofOfPaymentUploadedEvent::class => [
            \App\Listeners\SyncOrderToSheet::class,
        ],
        \Illuminate\Notifications\Events\NotificationFailed::class => [
            \App\Listeners\PruneInvalidFcmTokens::class,
        ],
        RefundRequestUpdated::class => [
            SendRefundStatusNotification::class,
        ],
        \Illuminate\Auth\Events\Login::class => [
            \App\Listeners\TrackUserSession::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Log::info('EventServiceProvider booting...');
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}
