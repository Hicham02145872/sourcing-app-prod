<?php

namespace App\Listeners;

use App\Events\QuotationAccepted;
use App\Models\User;
use App\Notifications\QuotationAccepted as QuotationAcceptedNotification;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendQuotationAcceptedNotification
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(QuotationAccepted $event)
    {
        $quotation = $event->quotation->refresh()->loadMissing(['order', 'sourcingRequest.user']);
        $lockKey = 'quotation_accepted_notification:'.$quotation->id;

        if (Cache::has($lockKey)) {
            Log::debug('QuotationAccepted notification already sent recently', [
                'quotation_id' => $quotation->id,
                'lock_key' => $lockKey,
            ]);

            return;
        }

        Cache::put($lockKey, true, now()->addSeconds(60));

        try {
            if (! $quotation->sourcingRequest) {
                Log::error('QuotationAccepted notification skipped: sourcingRequest is null', [
                    'quotation_id' => $quotation->id,
                    'user_id' => $quotation->user_id ?? null,
                    'relation_missing' => 'sourcingRequest',
                ]);
                Cache::forget($lockKey);

                return;
            }

            if (! $quotation->order) {
                Log::error('QuotationAccepted notification skipped: order is null', [
                    'quotation_id' => $quotation->id,
                    'sourcing_request_id' => $quotation->sourcing_request_id,
                    'user_id' => $quotation->sourcingRequest->user?->id,
                    'relation_missing' => 'order',
                ]);
                Cache::forget($lockKey);

                return;
            }

            $assignedAdminId = $quotation->sourcingRequest->assigned_to_admin_id;

            $admins = User::where('role', 'super_admin')
                ->when($assignedAdminId, function ($query) use ($assignedAdminId) {
                    $query->orWhere(function ($q) use ($assignedAdminId) {
                        $q->where('role', 'admin')
                            ->where('id', $assignedAdminId);
                    });
                })
                ->get();

            foreach ($admins as $admin) {
                /** @var \App\Models\User $admin */
                $admin->notify(new QuotationAcceptedNotification($quotation));
            }

            Log::info('QuotationAccepted notification sent to relevant admins', [
                'quotation_id' => $quotation->id,
                'sourcing_request_id' => $quotation->sourcing_request_id,
                'assigned_admin_id' => $assignedAdminId,
                'user_id' => $quotation->sourcingRequest->user?->id,
            ]);
        } catch (Exception $e) {
            Cache::forget($lockKey);
            Log::error('Failed to send QuotationAccepted notification', [
                'quotation_id' => $quotation->id,
                'sourcing_request_id' => $quotation->sourcing_request_id,
                'user_id' => $quotation->sourcingRequest?->user?->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
