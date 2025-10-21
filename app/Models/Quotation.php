<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending', // Initial state after creation by admin
        'sent', // Quotation has been sent to the client
        'accepted', // Client accepted the quotation
        'rejected', // Client rejected the quotation
        'expired', // Quotation validity period has passed
    ];

    protected $fillable = [
        'sourcing_request_id',
        'amount',
        'currency',
        'status',
        'unit_price',
        'commission_service',
        'unit_weight',
        'delivery_cost_china',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function sourcingRequest()
    {
        return $this->belongsTo(SourcingRequest::class);
    }

    public function order()
    {
        return $this->hasOne(SourcingOrder::class);
    }
}