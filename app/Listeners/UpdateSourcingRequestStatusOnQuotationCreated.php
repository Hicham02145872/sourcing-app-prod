<?php

namespace App\Listeners;

use App\Events\QuotationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
     * @param  \App\Events\QuotationCreated  $event
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
