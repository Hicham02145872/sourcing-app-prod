<?php

namespace App\Listeners;

use App\Events\QuotationAccepted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateSourcingRequestStatusOnQuotationAccepted
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\QuotationAccepted  $event
     * @return void
     */
    public function handle(QuotationAccepted $event)
    {
        $sourcingRequest = $event->quotation->sourcingRequest;
        if ($sourcingRequest->status === 'quoted') {
            $sourcingRequest->transitionTo('accepted');
        }
    }
}
