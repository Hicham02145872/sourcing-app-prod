<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sourcing_order_id',
        'sourcing_request_id',
        'user_id',
        'assigned_to_admin_id',
        'type',
        'status',
        'amount_requested',
        'amount_approved',
        'damaged_quantity',
        'reason_category',
        'reason_description',
        'admin_notes',
        'evidence_paths',
        'refund_proof_path',
    ];

    protected $casts = [
        'amount_requested' => 'decimal:2',
        'amount_approved' => 'decimal:2',
        'evidence_paths' => 'array',
    ];

    protected $dispatchesEvents = [
        'updated' => \App\Events\RefundRequestUpdated::class,
    ];

    public function sourcingOrder()
    {
        return $this->belongsTo(SourcingOrder::class);
    }

    public function sourcingRequest()
    {
        return $this->belongsTo(SourcingRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
}
