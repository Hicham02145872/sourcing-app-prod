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

        try {
            $report = $this->messaging->send($message);

            foreach ($report->getItems() as $item) {
                if ($item->isSuccess()) {
                    \Illuminate\Support\Facades\Log::info('FCM Message sent successfully.', [
                        'message_id' => $item->messageId(),
                        'fcm_token' => $fcmToken,
                        'notification_id' => $notification->id,
                    ]);
                } else {
                    \Illuminate\Support\Facades\Log::error('FCM Message failed to send.', [
                        'fcm_token' => $fcmToken,
                        'notification_id' => $notification->id,
                        'error_code' => $item->error()->messagingErrorCode()->value(),
                        'error_message' => $item->error()->getMessage(),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('FCM Sending Exception: ' . $e->getMessage(), [
                'fcm_token' => $fcmToken,
                'notification_id' => $notification->id,
                'exception' => $e,
            ]);
        }
    }
}
