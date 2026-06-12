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
    ];

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
