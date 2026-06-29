<?php

namespace App\Models;

use App\Jobs\DeleteCloudinaryAsset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationMedia extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::deleting(function ($media) {
            if ($media->cloudinary_public_id) {
                DeleteCloudinaryAsset::dispatch($media->cloudinary_public_id);
            }
        });
    }

    protected $table = 'quotation_media';

    protected $fillable = [
        'quotation_id',
        'file_path',
        'file_type',
        'sort_order',
        'cloudinary_public_id',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    /**
     * Get the full URL for the media file
     */
    public function getUrlAttribute(): string
    {
        return media_url($this->file_path);
    }

    /**
     * Check if this media is an image
     */
    public function isImage(): bool
    {
        return $this->file_type === 'image';
    }

    /**
     * Check if this media is a video
     */
    public function isVideo(): bool
    {
        return $this->file_type === 'video';
    }
}
