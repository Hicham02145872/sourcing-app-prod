<?php

namespace App\Models;

use App\Jobs\DeleteCloudinaryAsset;
use App\Services\SharedIdService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SourcingRequest extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::creating(function (SourcingRequest $sourcingRequest) {
            if (empty($sourcingRequest->shared_id)) {
                $sourcingRequest->shared_id = app(SharedIdService::class)->generate();
            }
        });

        static::deleting(function ($sourcingRequest) {
            if ($sourcingRequest->product_image) {
                Storage::disk('public')->delete($sourcingRequest->product_image);
            }
            if ($sourcingRequest->cloudinary_public_id) {
                DeleteCloudinaryAsset::dispatch($sourcingRequest->cloudinary_public_id);
            }
        });

        static::updating(function (SourcingRequest $sourcingRequest) {
            if ($sourcingRequest->isDirty('status')) {
                $timestamps = $sourcingRequest->status_timestamps ?? [];
                $timestamps[$sourcingRequest->status] = now()->toDateTimeString();
                $sourcingRequest->status_timestamps = $timestamps;
            }
        });
    }

    public const STATUSES = [
        'pending',
        'in_review',
        'quoted',
        'negotiating',
        'accepted',
        'completed',
        'rejected',
        'cancelled',
    ];

    /** Statuts à prioriser en tête de liste (ex: négociation en attente). */
    public const CRITICAL_STATUSES_FOR_LIST = ['negotiating'];

    protected $fillable = [
        'shared_id',
        'user_id',
        'product_name',
        'product_url',
        'product_image',
        'category_id',
        'note',
        'shipping_method',
        'status',
        'phone_number',
        'address',
        'latitude',
        'longitude',
        'sourcing_location',
        'assigned_to_admin_id',
        'assigned_at',
        'status_timestamps',
        'cloudinary_public_id',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'negotiated_at' => 'datetime',
        'accepted_at' => 'datetime',
        'status_timestamps' => 'array',
    ];

    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_to_admin_id');
    }

    /**
     * Get the translated status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return __($this->status);
    }

    /**
     * Get the custom display ID (multiple of 5).
     */
    public function getDisplayIdAttribute(): int
    {
        return $this->id * 5;
    }

    /**
     * Public reference: SBxxxxx for new records, legacy #display_id otherwise.
     */
    public function getReferenceIdAttribute(): string
    {
        if (! empty($this->shared_id)) {
            return (string) $this->shared_id;
        }

        if ($this->id) {
            return '#'.$this->display_id;
        }

        return '';
    }

    /**
     * Get the translated shipping method label.
     */
    public function getShippingMethodLabelAttribute(): string
    {
        return $this->shipping_method ? __($this->shipping_method) : __('N/A');
    }

    public function isAssigned()
    {
        return ! is_null($this->assigned_to_admin_id);
    }

    public function isAssignedTo(User $user)
    {
        return $this->assigned_to_admin_id === $user->id;
    }

    public function claim(User $user)
    {
        if ($this->isAssigned() && ! $this->isAssignedTo($user)) {
            throw new \Exception('Request is already assigned to another admin.');
        }

        $this->update([
            'assigned_to_admin_id' => $user->id,
            'assigned_at' => now(),
        ]);
    }

    public function release()
    {
        $this->update([
            'assigned_to_admin_id' => null,
            'assigned_at' => null,
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function destinations()
    {
        return $this->hasMany(SourcingRequestDestination::class);
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'sourcing_request_destinations')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }

    public function order()
    {
        return $this->hasOne(SourcingOrder::class);
    }

    public function canTransitionTo(string $newStatus, ?User $user = null): bool
    {
        $user = $user ?? auth()->user();

        // If no user is available (e.g., system action), allow if triggered by system logic
        // For now, if no user, we might want to default to 'admin' or deny.
        // Let's default to a 'system' role concept or check if user is null.
        if (! $user) {
            return true; // Or handle system-level transitions specifically
        }

        // Map super_admin to admin for transition checks
        $effectiveRole = ($user->role === 'super_admin') ? 'admin' : $user->role;

        $allowedTransitions = [
            'pending' => [
                'client' => ['rejected', 'cancelled'],
                'admin' => ['in_review', 'rejected', 'quoted'],
            ],
            'in_review' => [
                'client' => ['rejected', 'cancelled'],
                'admin' => ['quoted', 'rejected'],
            ],
            'quoted' => [
                'client' => ['accepted', 'rejected', 'negotiating', 'cancelled'],
                'admin' => ['accepted', 'rejected', 'cancelled'],
            ],
            'negotiating' => [
                'client' => ['rejected', 'cancelled'],
                'admin' => ['quoted', 'rejected'],
            ],
            'accepted' => [
                'admin' => ['completed', 'cancelled'],
            ],
            'rejected' => [], // No transitions from rejected
            'completed' => [], // No transitions from completed
            'cancelled' => [], // No transitions from cancelled
        ];

        // Check if the current status has defined transitions
        if (! isset($allowedTransitions[$this->status])) {
            return false;
        }

        // Check if the new status is allowed for the current status
        if (! in_array($newStatus, $allowedTransitions[$this->status][$effectiveRole] ?? [])) {
            return false;
        }

        // Additional checks (e.g., ownership)
        if ($user->isClient() && $this->user_id !== $user->id) {
            return false; // Client can only transition their own requests
        }

        return true;
    }

    public function transitionTo(string $newStatus, ?User $user = null): bool
    {
        $user = $user ?? auth()->user();

        if (! $this->canTransitionTo($newStatus, $user)) {
            $roleName = $user ? $user->role : 'system';
            throw new \Exception("Invalid status transition from '{$this->status}' to '{$newStatus}' for user role '{$roleName}'.");
        }

        $this->status = $newStatus;
        
        if ($newStatus === 'negotiating') {
            $this->negotiated_at = now();
        } elseif ($newStatus === 'accepted') {
            $this->accepted_at = now();
        }
        
        $this->save();

        \Illuminate\Support\Facades\Log::debug('DEBUG: SourcingRequestStatusChanged event DISPATCHED from SourcingRequest model', [
            'sourcing_request_id' => $this->id,
            'new_status' => $newStatus,
            'dispatched_by_user_id' => ($user ?? auth()->user())->id,
            'timestamp' => now()->toDateTimeString(),
        ]);
        event(new \App\Events\SourcingRequestStatusChanged($this, $user ?? auth()->user()));

        return true;
    }
}
