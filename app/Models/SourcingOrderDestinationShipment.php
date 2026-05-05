<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SourcingOrderDestinationShipment extends Model
{
    protected $fillable = [
        'sourcing_order_id',
        'sourcing_request_destination_id',
        'tracking_number',
        'tracking_carrier',
        'shipping_company_id',
    ];

    public function sourcingOrder(): BelongsTo
    {
        return $this->belongsTo(SourcingOrder::class);
    }

    public function sourcingRequestDestination(): BelongsTo
    {
        return $this->belongsTo(SourcingRequestDestination::class);
    }

    public function shippingCompany(): BelongsTo
    {
        return $this->belongsTo(ShippingCompany::class);
    }
}
