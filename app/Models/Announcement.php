<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcements';

    protected $fillable = [
        'message',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Announcements currently visible on the client banner
     * (active and within their date range — expired ones disappear automatically).
     */
    public function scopeVisible($query)
    {
        $today = now()->toDateString();

        return $query->where('is_active', true)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today);
    }

    public function status(): string
    {
        $today = now()->toDateString();

        if (! $this->is_active) {
            return 'inactive';
        }
        if ($this->end_date->toDateString() < $today) {
            return 'expired';
        }
        if ($this->start_date->toDateString() > $today) {
            return 'upcoming';
        }

        return 'active';
    }
}
