<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourcingOrder extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending_payment',
        'paid',
        'shipment_preparing',
        'in_transit_china',
        'arrival_uae',
        'customs_clearance_uae',
        'in_transit_uae',
        'arrival_destination_country',
        'customs_clearance_destination_country',
        'out_for_delivery',
        'delivered',
        'delivery_failed',
        'shipment_delayed',
        'shipment_returned',
        'shipment_canceled',
        'shipment_canceled',
        'order_completed',
        'refunded',
        'waiting_for_refund',
        'refund_approved',
        'refund_rejected',
    ];

    protected $fillable = [
        'user_id',
        'quotation_id',
        'total_amount',
        'status',
        'rejection_reason',
        'proof_of_payment_path',
        'tracking_number',
        'tracking_carrier',
        'product_cost_price',
        'shipping_cost_real',
        'rejection_loss_cost',
        'net_profit_or_loss',
        // Initial estimates for variance tracking
        'initial_estimated_product_cost',
        'initial_estimated_shipping_cost',
        'initial_estimated_other_costs',
        'cost_adjustment_notes',
        'assigned_to_admin_id',
        'refund_amount',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'product_cost_price',
        'shipping_cost_real',
        'rejection_loss_cost',
        'tracking_carrier',
        'net_profit_or_loss',
        'initial_estimated_product_cost',
        'initial_estimated_shipping_cost',
        'initial_estimated_other_costs',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'product_cost_price' => 'decimal:2',
        'shipping_cost_real' => 'decimal:2',
        'rejection_loss_cost' => 'decimal:2',
        'net_profit_or_loss' => 'decimal:2',
        'initial_estimated_product_cost' => 'decimal:2',
        'initial_estimated_shipping_cost' => 'decimal:2',
        'initial_estimated_other_costs' => 'decimal:2',
        'refund_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_to_admin_id');
    }

    public function canTransitionTo(string $newStatus): bool
    {
        $allowedTransitions = [
            'pending_payment' => ['paid', 'shipment_canceled'],
            'paid' => ['shipment_preparing', 'on_hold', 'shipment_canceled'],
            'shipment_preparing' => ['in_transit_china', 'shipment_delayed', 'shipment_canceled'],
            'in_transit_china' => ['arrival_uae', 'shipment_delayed', 'shipment_canceled'],
            'arrival_uae' => ['customs_clearance_uae', 'shipment_delayed', 'shipment_canceled'],
            'customs_clearance_uae' => ['in_transit_uae', 'shipment_delayed', 'shipment_canceled'],
            'in_transit_uae' => ['arrival_destination_country', 'shipment_delayed', 'shipment_canceled'],
            'arrival_destination_country' => ['customs_clearance_destination_country', 'shipment_delayed', 'shipment_canceled'],
            'customs_clearance_destination_country' => ['out_for_delivery', 'shipment_delayed', 'shipment_canceled'],
            'out_for_delivery' => ['delivered', 'delivery_failed', 'shipment_delayed', 'shipment_returned', 'shipment_canceled'],
            'delivered' => ['order_completed'],
            'delivery_failed' => ['shipment_returned', 'shipment_canceled'],
            'shipment_delayed' => ['shipment_preparing', 'in_transit_china', 'arrival_uae', 'in_transit_uae', 'arrival_destination_country', 'out_for_delivery', 'shipment_canceled'],
            'shipment_returned' => ['shipment_canceled'],
            'shipment_canceled' => [],
            'order_completed' => ['refunded'],
            'on_hold' => ['paid', 'shipment_canceled', 'refunded'],
            'refunded' => [],
        ];

        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }

    /**
     * Calculate variance between estimated and actual costs
     */
    public function getCostVariance(): ?array
    {
        if (is_null($this->initial_estimated_product_cost)) {
            return null;
        }

        $estimatedTotal = ($this->initial_estimated_product_cost ?? 0)
                        + ($this->initial_estimated_shipping_cost ?? 0)
                        + ($this->initial_estimated_other_costs ?? 0);

        $actualTotal = ($this->product_cost_price ?? 0)
                     + ($this->shipping_cost_real ?? 0)
                     + ($this->rejection_loss_cost ?? 0)
                     + ($this->refund_amount ?? 0);

        $variance = $actualTotal - $estimatedTotal;
        $variancePercentage = $estimatedTotal > 0
            ? ($variance / $estimatedTotal) * 100
            : 0;

        return [
            'estimated_total' => $estimatedTotal,
            'actual_total' => $actualTotal,
            'variance' => $variance,
            'variance_percentage' => $variancePercentage,
            'product_variance' => ($this->product_cost_price ?? 0) - ($this->initial_estimated_product_cost ?? 0),
            'shipping_variance' => ($this->shipping_cost_real ?? 0) - ($this->initial_estimated_shipping_cost ?? 0),
        ];
    }

    public function media()
    {
        return $this->hasMany(SourcingOrderMedia::class);
    }

    public function refundRequests()
    {
        return $this->hasMany(RefundRequest::class);
    }

    /**
     * Prepare data for Google Sheet sync
     */
    public function toGoogleSheetArray(): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'client_name' => $this->user->name,
            'client_email' => $this->user->email,
            'product_name' => $this->quotation->sourcingRequest->product_name,
            'quantity' => $this->quotation->sourcingRequest->destinations->sum('quantity'),
            'total_amount' => $this->total_amount,
            'currency' => $this->quotation->currency,
            'shipping_method' => $this->quotation->sourcingRequest->shipping_method,
            'tracking_number' => $this->tracking_number,
            'admin_assigned' => $this->assignedAdmin?->name ?? 'N/A',
            'net_profit' => $this->net_profit_or_loss,
        ];
    }

    /**
     * Get the custom display ID (always odd).
     */
    public function getDisplayIdAttribute(): int
    {
        return $this->id * 5;
    }
}
