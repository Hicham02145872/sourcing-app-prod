<?php

namespace App\Listeners;

use App\Events\QuotationRejected;
use App\Models\User;
use App\Notifications\QuotationRejected as QuotationRejectedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendQuotationRejectedNotification implements ShouldQueue
{
    use InteractsWithQueue;

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
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new QuotationRejectedNotification($event->quotation));
        }
    }
}
