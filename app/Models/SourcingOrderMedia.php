<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SourcingOrderMedia extends Model
{
    protected static function booted()
    {
        static::deleting(function ($media) {
            if ($media->file_path) {
                Storage::disk('public')->delete($media->file_path);
            }
        });
    }

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
