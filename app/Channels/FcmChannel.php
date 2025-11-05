<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging;

class FcmChannel
{
    protected $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $fcmToken = $notifiable->routeNotificationFor('fcm', $notification)) {
            return;
        }

        $message = $notification->toFcm($notifiable);

        if (! $message instanceof CloudMessage) {
            return;
        }

        $this->messaging->send($message);
    }
}
