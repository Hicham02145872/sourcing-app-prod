<?php

namespace App\Models;

use App\Jobs\DeleteCloudinaryAsset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RefundRequest extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::deleting(function ($refund) {
            if ($refund->refund_proof_path && !str_starts_with($refund->refund_proof_path, 'http')) {
                Storage::disk('public')->delete($refund->refund_proof_path);
            }
            if ($refund->cloudinary_public_id) {
                DeleteCloudinaryAsset::dispatch($refund->cloudinary_public_id);
            }
            $evidencePaths = $refund->evidence_paths ?? [];
            foreach ($evidencePaths as $evidencePath) {
                if (str_starts_with($evidencePath, 'http')) {
                    // We can't delete Cloudinary assets by URL without public ID
                    // This is a known limitation for array fields without individual public ID tracking
                    continue;
                }
                Storage::disk('public')->delete($evidencePath);
            }
        });
    }

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
