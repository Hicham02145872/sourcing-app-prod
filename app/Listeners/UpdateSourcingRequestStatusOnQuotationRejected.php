<?php

namespace App\Listeners;

use App\Events\QuotationRejected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
     * @param  \App\Events\QuotationRejected  $event
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
