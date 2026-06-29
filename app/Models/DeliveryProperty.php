<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryProperty extends Model
{
    protected $table = 'delivery_properties';

    protected $fillable = [
        'delivery_type',
        'title',
        'description',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForType($query, string $deliveryType)
    {
        return $query->where('delivery_type', $deliveryType);
    }
}
