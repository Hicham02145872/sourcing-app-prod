<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoogleSheetSyncLog extends Model
{
    protected $fillable = [
        'sourcing_order_id',
        'status',
        'action',
        'error_message',
        'error_code',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Get the sourcing order associated with this log.
     */
    public function sourcingOrder(): BelongsTo
    {
        return $this->belongsTo(SourcingOrder::class);
    }

    /**
     * Scope to get only successful syncs.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope to get only failed syncs.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'error');
    }

    /**
     * Get the last successful sync.
     */
    public static function lastSuccessfulSync()
    {
        return static::successful()->latest()->first();
    }

    /**
     * Get sync statistics.
     */
    public static function getStats(): array
    {
        $totalSuccess = static::successful()->count();
        $totalErrors = static::failed()->count();
        $lastSuccess = static::lastSuccessfulSync();
        $recentErrors = static::failed()->latest()->take(10)->get();

        return [
            'total_success' => $totalSuccess,
            'total_errors' => $totalErrors,
            'last_success_at' => $lastSuccess?->created_at,
            'last_success_order_id' => $lastSuccess?->sourcing_order_id,
            'recent_errors' => $recentErrors,
        ];
    }

    /**
     * Log a successful sync.
     */
    public static function logSuccess(int $orderId, array $data = []): self
    {
        return static::create([
            'sourcing_order_id' => $orderId,
            'status' => 'success',
            'action' => 'append',
            'data' => $data,
        ]);
    }

    /**
     * Log a failed sync.
     */
    public static function logError(int $orderId, string $message, ?string $code = null, array $data = []): self
    {
        return static::create([
            'sourcing_order_id' => $orderId,
            'status' => 'error',
            'action' => 'append',
            'error_message' => $message,
            'error_code' => $code,
            'data' => $data,
        ]);
    }

    public static function logStatusUpdateSuccess(int $orderId, string $newStatus): self
    {
        return static::create([
            'sourcing_order_id' => $orderId,
            'status' => 'success',
            'action' => 'update_status',
            'data' => ['new_status' => $newStatus],
        ]);
    }

    public static function logStatusUpdateError(int $orderId, string $message, ?string $newStatus = null): self
    {
        return static::create([
            'sourcing_order_id' => $orderId,
            'status' => 'error',
            'action' => 'update_status',
            'error_message' => $message,
            'data' => ['new_status' => $newStatus],
        ]);
    }

    /**
     * Get user-friendly action description.
     */
    public function getFriendlyAction(): string
    {
        return match ($this->action) {
            'append' => __('New order added to Google Sheets'),
            'update_status' => __('Order status updated'),
            'update' => __('Order information updated'),
            'sync' => __('Order synchronized'),
            default => __('Action performed'),
        };
    }

    /**
     * Get status icon for visual recognition.
     */
    public function getStatusIcon(): string
    {
        return match ($this->status) {
            'success' => '✅',
            'error' => '❌',
            'warning' => '⚠️',
            default => 'ℹ️',
        };
    }

    /**
     * Get user-friendly status label.
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'success' => __('Success'),
            'error' => __('Error'),
            'warning' => __('Warning'),
            default => __('Info'),
        };
    }

    /**
     * Get user-friendly error message.
     */
    public function getFriendlyErrorMessage(): string
    {
        if ($this->status === 'success') {
            return '';
        }

        $errorMessage = $this->error_message ?? '';

        // Common Google Sheets API errors
        if (str_contains($errorMessage, 'does not have permission') || str_contains($errorMessage, 'forbidden')) {
            return __('Unable to access Google Sheets. Please check sharing permissions.');
        }

        if (str_contains($errorMessage, 'not found') || str_contains($errorMessage, '404')) {
            return __('Google Sheet not found. Please verify the sheet URL in settings.');
        }

        if (str_contains($errorMessage, 'quota') || str_contains($errorMessage, 'rate limit')) {
            return __('Google Sheets API limit reached. Sync will retry automatically.');
        }

        if (str_contains($errorMessage, 'credentials') || str_contains($errorMessage, 'authentication')) {
            return __('Google Sheets connection not configured properly. Please check credentials.');
        }

        if (str_contains($errorMessage, 'network') || str_contains($errorMessage, 'timeout')) {
            return __('Connection to Google Sheets failed. Please check your internet connection.');
        }

        if (str_contains($errorMessage, 'invalid') || str_contains($errorMessage, 'malformed')) {
            return __('Invalid data format. Please contact support.');
        }

        // If no specific match, return a generic friendly message
        return __('An error occurred while syncing to Google Sheets.');
    }

    /**
     * Get actionable suggestion based on error type.
     */
    public function getSuggestion(): ?string
    {
        if ($this->status === 'success') {
            return null;
        }

        $errorMessage = $this->error_message ?? '';

        if (str_contains($errorMessage, 'does not have permission') || str_contains($errorMessage, 'forbidden')) {
            $settingsUrl = route('admin.google-sheets.settings');

            return __('Make sure you have shared your Google Sheet with the service account email. <a href=":url" class="text-blue-600 hover:underline">Check settings</a>', ['url' => $settingsUrl]);
        }

        if (str_contains($errorMessage, 'not found') || str_contains($errorMessage, '404')) {
            $settingsUrl = route('admin.google-sheets.settings');

            return __('Verify the Google Sheet URL is correct. <a href=":url" class="text-blue-600 hover:underline">Update settings</a>', ['url' => $settingsUrl]);
        }

        if (str_contains($errorMessage, 'quota') || str_contains($errorMessage, 'rate limit')) {
            return __('This is temporary. The system will automatically retry in a few minutes.');
        }

        if (str_contains($errorMessage, 'credentials') || str_contains($errorMessage, 'authentication')) {
            $settingsUrl = route('admin.google-sheets.settings');

            return __('Re-upload your Google Sheets credentials file. <a href=":url" class="text-blue-600 hover:underline">Go to settings</a>', ['url' => $settingsUrl]);
        }

        if (str_contains($errorMessage, 'network') || str_contains($errorMessage, 'timeout')) {
            return __('Check your connection and wait a few minutes before trying again.');
        }

        return __('If this problem persists, please contact technical support.');
    }

    /**
     * Get formatted time for display.
     */
    public function getFormattedTime(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get full timestamp for tooltip.
     */
    public function getFullTimestamp(): string
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }
}
