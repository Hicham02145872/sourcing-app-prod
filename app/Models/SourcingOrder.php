<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourcingOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quotation_id',
        'total_amount',
        'status',
        'tracking_number',
        'tracking_carrier',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}