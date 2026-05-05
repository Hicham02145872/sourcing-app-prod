<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\AdminNotificationMailGate;
use Illuminate\Notifications\Events\NotificationSending;

class FilterAdminNotificationMailChannel
{
    public function __construct(
        protected AdminNotificationMailGate $gate
    ) {}

    /**
     * @return bool false pour annuler l'envoi sur le canal courant
     */
    public function handle(NotificationSending $event): bool
    {
        if ($event->channel !== 'mail') {
            return true;
        }

        $notifiable = $event->notifiable;
        if (! $notifiable instanceof User || ! $notifiable->isAdmin()) {
            return true;
        }

        return $this->gate->allowsMail($notifiable, $event->notification);
    }
}
