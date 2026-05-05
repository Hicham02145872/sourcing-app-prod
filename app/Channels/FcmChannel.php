<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;

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

        try {
            $response = $this->messaging->send($message);

            \Illuminate\Support\Facades\Log::info('FCM Message sent successfully.', [
                'message_id' => $response,
                'fcm_token' => $fcmToken,
                'notification_id' => $notification->id,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('FCM Sending Exception: '.$e->getMessage(), [
                'fcm_token' => $fcmToken,
                'notification_id' => $notification->id,
                'exception_class' => get_class($e),
            ]);

            // Re-throw to trigger NotificationFailed event
            throw $e;
        }
    }
}
