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
        'order_completed',
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
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
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
            'order_completed' => [],
            'on_hold' => ['paid', 'shipment_canceled'],
        ];

        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }
}