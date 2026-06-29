<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingFeeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_fee_id',
        'transport_type',
        'item_style',
        'price_per_kg',
        'price_per_kg_dubai',
        'price_per_kg_china_to_dubai',
        'price_per_kg_dubai_to_africa',
        'estimation_days',
        'estimation_unit',
    ];

    public function shippingFee(): BelongsTo
    {
        return $this->belongsTo(ShippingFee::class);
    }
}
