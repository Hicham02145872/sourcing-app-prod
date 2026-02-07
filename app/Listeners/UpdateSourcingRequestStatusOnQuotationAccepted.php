<?php

namespace App\Listeners;

use App\Events\QuotationAccepted;

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
