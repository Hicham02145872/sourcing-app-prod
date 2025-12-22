<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleSheetSetting extends Model
{
    protected $fillable = ['sheet_id', 'sheet_name', 'synced_fields'];

    protected $casts = [
        'synced_fields' => 'array',
    ];
}
