<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'google_sheet_id',
        'sheet_name',
        'is_active',
        'lark_app_id',
        'lark_app_secret',
        'lark_base_token',
        'lark_table_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Sourcing orders assigned to this shipping company.
     */
    public function sourcingOrders(): HasMany
    {
        return $this->hasMany(SourcingOrder::class);
    }
}
