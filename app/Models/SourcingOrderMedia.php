<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SourcingOrderMedia extends Model
{
    protected $fillable = [
        'sourcing_order_id',
        'file_path',
        'file_type',
        'file_name',
    ];

    public function sourcingOrder()
    {
        return $this->belongsTo(SourcingOrder::class);
    }
}
