<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'air_arrival_time',
        'sea_arrival_time',
        'train_arrival_time',
        'currency',
        'unit',
        'air_unit',
        'sea_unit',
        'train_unit',
    ];

    public function getUnitForTransport(string $transportType): string
    {
        $normalizedType = strtolower($transportType);

        $unit = match ($normalizedType) {
            'air' => $this->air_unit,
            'sea' => $this->sea_unit,
            'train' => $this->train_unit,
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
