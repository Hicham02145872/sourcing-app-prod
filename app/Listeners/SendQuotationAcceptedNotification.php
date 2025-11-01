<?php

namespace App\Listeners;

use App\Events\QuotationAccepted;
use App\Models\User;
use App\Notifications\QuotationAccepted as QuotationAcceptedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendQuotationAcceptedNotification implements ShouldQueue
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
     * @param  \App\Events\QuotationAccepted  $event
     * @return void
     */
    public function handle(QuotationAccepted $event)
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new QuotationAcceptedNotification($event->quotation));
        }
    }
}
