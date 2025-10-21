<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourcingRequest extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending',
        'in_review',
        'quoted',
        'accepted',
        'completed',
        'rejected',
        'cancelled',
    ];

    protected $fillable = [
        'user_id',
        'product_name',
        'product_url',
        'product_image',
        'category_id',
        'note',
        'shipping_method',
        'status',
        'phone_number',
        'address',
        'latitude',
        'longitude',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function destinations()
    {
        return $this->hasMany(SourcingRequestDestination::class);
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'sourcing_request_destinations')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }

    public function order()
    {
        return $this->hasOneThrough(SourcingOrder::class, Quotation::class);
    }
}