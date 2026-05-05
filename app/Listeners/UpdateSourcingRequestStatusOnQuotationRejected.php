<?php

namespace App\Listeners;

use App\Events\QuotationRejected;

class UpdateSourcingRequestStatusOnQuotationRejected
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
     * @return void
     */
    public function handle(QuotationRejected $event)
    {
        $sourcingRequest = $event->quotation->sourcingRequest;
        if ($sourcingRequest->status === 'quoted') {
            $sourcingRequest->transitionTo('rejected');
        }
    }
}
