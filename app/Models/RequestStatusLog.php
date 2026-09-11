<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestStatusLog extends Model
{
    protected $fillable = [
        'sourcing_request_id',
        'from_status',
        'to_status',
        'changed_by_user_id',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function sourcingRequest(): BelongsTo
    {
        return $this->belongsTo(SourcingRequest::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}