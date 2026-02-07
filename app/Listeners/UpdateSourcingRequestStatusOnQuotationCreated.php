<?php

namespace App\Listeners;

use App\Events\QuotationCreated;

class UpdateSourcingRequestStatusOnQuotationCreated
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
    public function handle(QuotationCreated $event)
    {
        $sourcingRequest = $event->quotation->sourcingRequest;
        if ($sourcingRequest->status === 'in_review') {
            $sourcingRequest->transitionTo('quoted');
        }
    }
}
