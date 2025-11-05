<?php

namespace App\Listeners;

use App\Events\QuotationCreated;
use App\Notifications\QuotationCreated as QuotationCreatedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class SendQuotationCreatedNotification
{
    public $tries = 3;
    public $backoff = [60, 300, 900];

    /**
     * Handle the event.
     */
    public function handle(QuotationCreated $event): void
    {
        try {
            $quotation = $event->quotation;
            
            // Validate quotation and relationships
            if (!$quotation || !$quotation->sourcingRequest) {
                Log::warning('QuotationCreated: Invalid quotation or missing sourcing request', [
                    'quotation_id' => $quotation->id ?? null,
                ]);
                return;
            }

            $user = $quotation->sourcingRequest->user;

            if (!$user) {
                Log::warning('QuotationCreated: User not found for quotation', [
                    'quotation_id' => $quotation->id,
                ]);
                return;
            }

            // Check for duplicate notifications using atomic lock
            $lockKey = 'quotation_notification:' . $quotation->id;
            
            if (Cache::has($lockKey)) {
                Log::debug('QuotationCreated notification already sent recently', [
                    'quotation_id' => $quotation->id,
                    'lock_key' => $lockKey,
                ]);
                return;
            }

            // Set lock for 60 seconds to prevent duplicates
            Cache::put($lockKey, true, now()->addSeconds(60));

            // Check if user has FCM token
            if (!$user->fcm_token) {
                Log::warning('QuotationCreated: User has no FCM token', [
                    'quotation_id' => $quotation->id,
                    'user_id' => $user->id,
                ]);
                // Still send via other channels (Mail, Database)
                $this->sendNotification($quotation, $user);
                return;
            }

            // Send notification via all channels
            $this->sendNotification($quotation, $user);

            Log::info('QuotationCreated notification sent successfully', [
                'quotation_id' => $quotation->id,
                'user_id' => $user->id,
                'has_fcm_token' => !empty($user->fcm_token),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to send QuotationCreated notification', [
                'quotation_id' => $quotation->id ?? null,
                'user_id' => $user->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Release lock on error
            if (isset($lockKey)) {
                Cache::forget($lockKey);
            }

            throw $e;
        }
    }

    /**
     * Send notification through configured channels
     */
    private function sendNotification($quotation, $user): void
    {
        try {
            $user->notify(new QuotationCreatedNotification($quotation));
        } catch (Exception $e) {
            Log::error('Error sending notification to user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}