<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending', // Initial state after creation by admin
        'approved', // Admin has approved the quotation
        'sent', // Quotation has been sent to the client
        'accepted', // Client accepted the quotation
        'rejected', // Client rejected the quotation
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
        'unit_weight',
        'delivery_cost_china',
        // Financial estimation fields
        'estimated_product_cost',
        'estimated_shipping_cost',
        'estimated_other_costs',
        'estimated_net_profit',
        'weight_unit',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'commission_service' => 'decimal:2',
        'unit_weight' => 'decimal:2',
        'delivery_cost_china' => 'decimal:2',
        'estimated_product_cost' => 'decimal:2',
        'estimated_shipping_cost' => 'decimal:2',
        'estimated_other_costs' => 'decimal:2',
        'estimated_net_profit' => 'decimal:2',
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

        return $this->amount - $totalCosts;
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
}
