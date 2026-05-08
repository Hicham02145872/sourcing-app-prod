<?php

namespace App\Notifications;

use App\Services\AdminNotificationMailGate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Base class for admin notifications that respect mail preferences.
 * All admin notifications should extend this class.
 */
abstract class BaseAdminNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     * Filters mail channel based on user preferences via AdminNotificationMailGate.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        // Check if user should receive this email via AdminNotificationMailGate
        if ($notifiable->isAdmin() && app(AdminNotificationMailGate::class)->allowsMail($notifiable, $this)) {
            $channels[] = 'mail';
        }

        // Add FCM if token exists
        if (! empty($notifiable->fcm_token) && method_exists($this, 'toFcm')) {
            $channels[] = 'fcm';
        }

        return $channels;
    }
}
