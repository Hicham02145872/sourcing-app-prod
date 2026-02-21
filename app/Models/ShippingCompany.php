<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tracking_provider',
        'carrier_options',
        'google_sheet_id',
        'sheet_name',
        'is_active',
        'lark_app_id',
        'lark_app_secret',
        'lark_base_token',
        'lark_table_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'carrier_options' => 'array',
    ];

    /**
     * Carrier options with labels for admin dropdown (e.g. [['key' => 'gcc', 'label' => 'GCC'], ...]).
     */
    public function getCarrierOptionsWithLabels(): array
    {
        $options = $this->carrier_options ?? [];
        if (empty($options)) {
            return [];
        }
        $labels = config('tracking.carrier_labels', []);
        $out = [];
        foreach ($options as $key) {
            $out[] = [
                'key' => $key,
                'label' => $labels[$key] ?? strtoupper($key),
            ];
        }
        return $out;
    }

    /**
     * Sourcing orders assigned to this shipping company.
     */
    public function sourcingOrders(): HasMany
    {
        return $this->hasMany(SourcingOrder::class);
    }
}
