<?php

namespace App\Models;

use App\Jobs\DeleteCloudinaryAsset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PaymentMethod extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::deleting(function ($method) {
            if ($method->logo_path && !str_starts_with($method->logo_path, 'http')) {
                Storage::disk('public')->delete($method->logo_path);
            }
            if ($method->cloudinary_public_id) {
                DeleteCloudinaryAsset::dispatch($method->cloudinary_public_id);
            }
        });
    }

    protected $fillable = [
        'name',
        'logo_path',
        'details',
        'is_active',
    ];

    protected $casts = [
        'details' => 'array',
        'is_active' => 'boolean',
    ];
}
