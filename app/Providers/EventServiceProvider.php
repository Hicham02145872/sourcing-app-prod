<?php

namespace App\Providers;

use App\Events\QuotationCreated;
use App\Listeners\UpdateSourcingRequestStatusOnQuotationCreated;
use App\Events\QuotationRejected;
use App\Listeners\UpdateSourcingRequestStatusOnQuotationAccepted;
use App\Listeners\SendQuotationAcceptedNotification;
use App\Listeners\UpdateSourcingRequestStatusOnQuotationRejected;
use App\Listeners\SendQuotationRejectedNotification;
use App\Events\SourcingRequestStatusChanged;
use App\Listeners\SendSourcingRequestStatusChangeNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        QuotationCreated::class => [
            UpdateSourcingRequestStatusOnQuotationCreated::class,
            \App\Listeners\SendQuotationCreatedNotification::class,
        ],
        \App\Events\QuotationAccepted::class => [
            UpdateSourcingRequestStatusOnQuotationAccepted::class,
            SendQuotationAcceptedNotification::class,
        ],
        QuotationRejected::class => [
            UpdateSourcingRequestStatusOnQuotationRejected::class,
            SendQuotationRejectedNotification::class,
        ],
        SourcingRequestStatusChanged::class => [
            SendSourcingRequestStatusChangeNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
