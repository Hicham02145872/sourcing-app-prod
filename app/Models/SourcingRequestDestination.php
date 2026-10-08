<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourcingRequestDestination extends Model
{
    use HasFactory;

    protected $fillable = [
        'sourcing_request_id',
        'country_id',
        'service_id',
        'quantity',
        'address',
        'label_address',
        'sourcing_location',
    ];

    /**
     * Routing effective for this destination: its own choice when set,
     * otherwise the request-wide default.
     */
    public function getEffectiveSourcingLocationAttribute(): string
    {
        return $this->sourcing_location
            ?: $this->sourcingRequest?->sourcing_location
            ?: 'china';
    }

    /**
     * Friendly label of this destination's routing.
     * 'china' → Direct (from China), 'dubai' → Indirect (via Dubai).
     */
    public function getRoutingLabelAttribute(): string
    {
        return match ($this->effective_sourcing_location) {
            'china' => __('Direct (from China)'),
            'dubai' => __('Indirect (via Dubai)'),
            default => __('Not specified'),
        };
    }

    public function sourcingRequest()
    {
        return $this->belongsTo(SourcingRequest::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
