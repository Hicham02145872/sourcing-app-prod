<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingFeeItem extends Model
{
    protected $fillable = [
        'shipping_fee_id',
        'transport_type',
        'item_style',
        'price_16_49',
        'price_50_99',
        'price_100_499',
        'price_plus_500',
        'estimation_days',
    ];

    public function shippingFee(): BelongsTo
    {
        return $this->belongsTo(ShippingFee::class);
    }
}
