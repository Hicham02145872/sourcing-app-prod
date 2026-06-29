<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryNotice extends Model
{
    protected $table = 'delivery_notices';

    protected $fillable = [
        'delivery_type',
        'body_text',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
