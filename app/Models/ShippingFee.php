<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'air_direct_arrival_time',
        'sea_arrival_time',
        'air_indirect_arrival_time',
        'currency',
        'unit',
        'air_direct_unit',
        'sea_unit',
        'air_indirect_unit',
        'is_air_direct_visible',
        'is_air_indirect_visible',
        'is_sea_visible',
    ];

    protected $casts = [
        'is_air_direct_visible' => 'boolean',
        'is_air_indirect_visible' => 'boolean',
        'is_sea_visible' => 'boolean',
    ];

    public function getUnitForTransport(string $transportType): string
    {
        $normalizedType = strtolower($transportType);

        $unit = match ($normalizedType) {
            'air_direct' => $this->air_direct_unit,
            'sea' => $this->sea_unit,
            'air_indirect' => $this->air_indirect_unit,
            default => null,
        };

        if (! empty($unit)) {
            return strtoupper($unit);
        }

        if (! empty($this->unit)) {
            return strtoupper($this->unit);
        }

        return $normalizedType === 'sea' ? 'CBM' : 'KG';
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShippingFeeItem::class);
    }
}
