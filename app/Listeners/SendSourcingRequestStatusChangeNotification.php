<?php

namespace App\Listeners;

use App\Events\SourcingRequestStatusChanged;
use App\Notifications\SourcingRequestStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;

class SendSourcingRequestStatusChangeNotification implements ShouldQueue
{
    use InteractsWithQueue;

    protected $messaging;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SourcingRequestStatusChanged  $event
     * @return void
     */
    public function handle(SourcingRequestStatusChanged $event)
    {
        $sourcingRequest = $event->sourcingRequest;
        $user = $sourcingRequest->user;

        $notification = new SourcingRequestStatusUpdated($sourcingRequest);

        // Notify the user via mail and database
        $user->notify($notification);

        // Manually send the FCM notification
        if ($user->fcm_token) {
            try {
                $fcmMessage = $notification->toFcm($user);
                if ($fcmMessage) {
                    $this->messaging->send($fcmMessage);
                }
            } catch (\Exception $e) {
                // Log the error but don't block the user
                Log::error('FCM notification failed to send: '.$e->getMessage());
            }
        }
    }
}
