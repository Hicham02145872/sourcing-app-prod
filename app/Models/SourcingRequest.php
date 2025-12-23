<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourcingRequest extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending',
        'in_review',
        'quoted',
        'accepted',
        'completed',
        'rejected',
        'cancelled',
    ];

    protected $fillable = [
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
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_to_admin_id');
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
        return $this->hasOneThrough(SourcingOrder::class, Quotation::class);
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
                'client' => ['accepted', 'rejected', 'cancelled'],
                'admin' => ['accepted', 'rejected', 'cancelled'],
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
