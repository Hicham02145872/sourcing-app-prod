<?php

namespace App\Listeners;

use App\Events\QuotationAccepted;
use Illuminate\Support\Facades\Log;

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
        $sourcingRequest = $event->quotation?->sourcingRequest;

        if (! $sourcingRequest) {
            Log::warning('Skipping sourcing request status transition: sourcingRequest is null', [
                'quotation_id' => $event->quotation?->id,
                'user_id' => $event->quotation?->user_id,
                'relation_missing' => 'sourcingRequest',
            ]);

            return;
        }

        if ($sourcingRequest->status === 'quoted') {
            $sourcingRequest->transitionTo('accepted');
        }
    }
}
