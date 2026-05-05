<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevQueryLog extends Model
{
    protected $fillable = [
        'user_id',
        'query',
        'is_write',
        'rows_affected',
        'ip',
        'request_id',
    ];

    protected function casts(): array
    {
        return [
            'is_write' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
