<?php

namespace App\Models;

use App\Jobs\DeleteCloudinaryAsset;
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
            if ($media->cloudinary_public_id) {
                DeleteCloudinaryAsset::dispatch($media->cloudinary_public_id);
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
