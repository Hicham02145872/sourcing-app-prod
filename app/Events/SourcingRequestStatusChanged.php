<?php

namespace App\Events;

use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SourcingRequestStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sourcingRequest;
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\SourcingRequest  $sourcingRequest
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct(SourcingRequest $sourcingRequest, User $user)
    {
        $this->sourcingRequest = $sourcingRequest;
        $this->user = $user;
    }
}
