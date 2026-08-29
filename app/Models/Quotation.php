<?php

namespace App\Models;

use App\Jobs\DeleteCloudinaryAsset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Quotation extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::deleting(function ($quotation) {
            if ($quotation->real_product_image && !str_starts_with($quotation->real_product_image, 'http')) {
                Storage::disk('public')->delete($quotation->real_product_image);
            }
            if ($quotation->cloudinary_public_id) {
                DeleteCloudinaryAsset::dispatch($quotation->cloudinary_public_id);
            }
            $qualityOptions = $quotation->quality_options ?? [];
            foreach ($qualityOptions as $option) {
                if (!empty($option['image_path'])) {
                    if (str_starts_with($option['image_path'], 'http')) {
                        DeleteCloudinaryAsset::dispatch($quotation->cloudinary_public_id);
                    } else {
                        Storage::disk('public')->delete($option['image_path']);
                    }
                }
            }
        });
    }

    public const STATUSES = [
        'pending', // Initial state after creation by admin
        'approved', // Admin has approved the quotation
        'sent', // Quotation has been sent to the client
        'accepted', // Client accepted the quotation
        'rejected', // Client rejected the quotation
        'negotiating', // Client requested negotiation
        'expired', // Quotation validity period has passed
    ];

    protected $fillable = [
        'sourcing_request_id',
        'assigned_to_admin_id',
        'amount',
        'currency',
        'status',
        'unit_price',
        'commission_service',
        'delivery_cost_china',
        // Financial estimation fields
        'estimated_product_cost',
        'estimated_shipping_cost',
        'estimated_other_costs',
        'estimated_net_profit',
        'negotiation_notes',
        'admin_negotiation_reply',
        'actual_sourcing_location',
        'sourcing_note',
        'comments',
        'real_product_image',
        'supplier_url',
        'quality_options',
        'selected_quality',
        'cloudinary_public_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'commission_service' => 'decimal:2',
        'delivery_cost_china' => 'decimal:2',
        'estimated_product_cost' => 'decimal:2',
        'estimated_shipping_cost' => 'decimal:2',
        'estimated_other_costs' => 'decimal:2',
        'estimated_net_profit' => 'decimal:2',
        'quality_options' => 'array',
    ];

    public function sourcingRequest()
    {
        return $this->belongsTo(SourcingRequest::class);
    }

    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_to_admin_id');
    }

    public function order()
    {
        return $this->hasOne(SourcingOrder::class);
    }

    public function media()
    {
        return $this->hasMany(QuotationMedia::class)->orderBy('sort_order');
    }

    /**
     * Calculate estimated net profit
     */
    public function calculateEstimatedProfit(): ?float
    {
        if (is_null($this->estimated_product_cost)) {
            return null;
        }

        $totalCosts = ($this->estimated_product_cost ?? 0)
                    + ($this->estimated_shipping_cost ?? 0)
                    + ($this->estimated_other_costs ?? 0);

        return (float) $this->amount - $totalCosts;
    }

    /**
     * Get estimated profit margin percentage
     */
    public function getEstimatedProfitMarginPercentage(): ?float
    {
        if (is_null($this->estimated_net_profit) || $this->amount == 0) {
            return null;
        }

        return ($this->estimated_net_profit / $this->amount) * 100;
    }

    /**
     * Get the custom display ID (multiple of 5).
     */
    public function getDisplayIdAttribute(): int
    {
        return $this->id * 5;
    }

    /**
     * Get the weight and unit for a quality option, honoring the selected quality.
     *
     * Falls back to the medium quality when the requested quality has no weight,
     * and returns null when no weight is available.
     */
    public function weightForQuality(?string $quality = null): ?array
    {
        $options = $this->quality_options ?? [];

        $quality = $quality ?? $this->selected_quality ?? 'medium';

        if (!isset($options[$quality]['weight']) || $options[$quality]['weight'] === null || $options[$quality]['weight'] === '') {
            $quality = 'medium';
        }

        if (!isset($options[$quality]['weight']) || $options[$quality]['weight'] === null || $options[$quality]['weight'] === '') {
            return null;
        }

        return [
            'weight' => (float) $options[$quality]['weight'],
            'weight_unit' => $options[$quality]['weight_unit'] ?? 'g',
        ];
    }
}
