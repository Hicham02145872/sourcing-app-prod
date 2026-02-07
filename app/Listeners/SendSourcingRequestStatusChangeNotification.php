<?php

namespace App\Listeners;

use App\Events\SourcingRequestStatusChanged;
use App\Notifications\SourcingRequestStatusUpdated as SourcingRequestStatusUpdatedNotification;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        $lockKey = 'sourcing_notification:'.$sourcingRequest->id.':'.$sourcingRequest->status;

        if (Cache::has($lockKey)) {
            Log::debug('Notification already sent recently', ['sourcing_request_id' => $sourcingRequest->id, 'lock_key' => $lockKey]);

            return;
        }

        Cache::put($lockKey, true, 60); // Verrou pour 60 secondes

        try {
            // Notifier le client s'il s'agit d'une action admin
            if ($adminUser->isAdmin()) {
                $clientUser->notify(new SourcingRequestStatusUpdatedNotification($sourcingRequest));
                Log::info('SourcingRequestStatusUpdated notification sent to client', [
                    'sourcing_request_id' => $sourcingRequest->id,
                    'user_id' => $clientUser->id,
                ]);
            }

            // Notifier l'admin s'il s'agit d'une action client
            if ($adminUser->isClient()) {
                $assignedAdmin = $sourcingRequest->assignedAdmin;
                if ($assignedAdmin) {
                    $notification = $sourcingRequest->status === 'negotiating'
                        ? new \App\Notifications\QuotationNegotiationRequested($sourcingRequest)
                        : new \App\Notifications\AdminSourcingRequestStatusUpdated($sourcingRequest);

                    $assignedAdmin->notify($notification);
                    Log::info('SourcingRequest status update notification sent to admin', [
                        'sourcing_request_id' => $sourcingRequest->id,
                        'admin_id' => $assignedAdmin->id,
                        'type' => get_class($notification),
                    ]);
                }
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
