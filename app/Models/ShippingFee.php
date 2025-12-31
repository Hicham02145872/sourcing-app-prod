<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingFee extends Model
{
    protected $fillable = [
        'country_id',
        'sea_fee',
        'train_fee',
        'air_normal_fee',
        'air_brand_fee',
        'air_battery_fee',
        'air_liquid_fee',
        'currency',
        'unit',
    ];

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShippingFeeItem::class);
    }
}
