<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'sourcing_request_id',
        'amount',
        'currency',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function sourcingRequest()
    {
        return $this->belongsTo(SourcingRequest::class);
    }

    public function order()
    {
        return $this->hasOne(SourcingOrder::class);
    }
}