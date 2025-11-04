<?php

namespace App\Events;

use App\Models\SourcingOrder;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SourcingOrderStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sourcingOrder;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\SourcingOrder  $sourcingOrder
     * @return void
     */
    public function __construct(SourcingOrder $sourcingOrder)
    {
        $this->sourcingOrder = $sourcingOrder;
    }
}
