<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'is_direct'];

    public function getFlagUrlAttribute()
    {
        return asset('images/flags/'.strtolower($this->code).'.svg');
    }

    public function sourcingRequestDestinations()
    {
        return $this->hasMany(SourcingRequestDestination::class);
    }

    public function shippingFee()
    {
        return $this->hasOne(ShippingFee::class);
    }
}
