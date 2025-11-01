<?php

namespace App\Events;

use App\Models\Quotation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuotationCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $quotation;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return void
     */
    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }
}
