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
    ];

    public function shippingFee(): BelongsTo
    {
        return $this->belongsTo(ShippingFee::class);
    }
}
