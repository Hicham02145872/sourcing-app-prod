<?php

namespace App\Listeners;

use App\Events\SourcingRequestStatusChanged;
use App\Notifications\SourcingRequestStatusUpdated as SourcingRequestStatusUpdatedNotification;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SendSourcingRequestStatusChangeNotification
{
    /**
     * Handle the event.
     */
    public function handle(SourcingRequestStatusChanged $event): void
    {
        \Illuminate\Support\Facades\Log::debug('DEBUG: SendSourcingRequestStatusChangeNotification listener TRIGGERED', [
            'sourcing_request_id' => $event->sourcingRequest->id,
            'status' => $event->sourcingRequest->status,
            'event_user_id' => $event->user->id,
            'timestamp' => now()->toDateTimeString(),
        ]);

        $sourcingRequest = $event->sourcingRequest;
        $clientUser = $sourcingRequest->user;
        $adminUser = $event->user;

        // Empêcher les doublons avec un verrou
        $lockKey = 'sourcing_notification:' . $sourcingRequest->id . ':' . $sourcingRequest->status;
        
        if (Cache::has($lockKey)) {
            Log::debug('Notification already sent recently', ['sourcing_request_id' => $sourcingRequest->id, 'lock_key' => $lockKey]);
            return;
        }

        Cache::put($lockKey, true, 60); // Verrou pour 60 secondes

        try {
            // Notifier le client
            $clientUser->notify(new SourcingRequestStatusUpdatedNotification($sourcingRequest));

            Log::info('SourcingRequestStatusUpdated notification sent to client for Sourcing Request', [
                'sourcing_request_id' => $sourcingRequest->id,
                'user_id' => $clientUser->id,
                'status' => $sourcingRequest->status,
            ]);

            // Notifier l'admin si différent du client
            if ($clientUser->id !== $adminUser->id) {
                $adminUser->notify(new SourcingRequestStatusUpdatedNotification($sourcingRequest));
                Log::info('SourcingRequestStatusUpdated notification sent to admin for Sourcing Request', [
                    'sourcing_request_id' => $sourcingRequest->id,
                    'user_id' => $adminUser->id,
                    'status' => $sourcingRequest->status,
                ]);
            }

        } catch (Exception $e) {
            Cache::forget($lockKey); // Libérer le verrou en cas d'erreur
            Log::error('Failed to send SourcingRequestStatusUpdated notification.', [
                'sourcing_request_id' => $sourcingRequest->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}