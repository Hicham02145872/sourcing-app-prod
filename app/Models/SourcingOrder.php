<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourcingOrder extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending_payment',
        'paid',
        'shipped',
        'delivered',
        'completed',
        'cancelled',
        'on_hold',
    ];

    protected $fillable = [
        'user_id',
        'quotation_id',
        'total_amount',
        'status',
        'rejection_reason',
        'proof_of_payment_path',
        'tracking_number',
        'tracking_carrier',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function canTransitionTo(string $newStatus): bool
    {
        $allowedTransitions = [
            'pending_payment' => ['paid', 'cancelled'],
            'paid' => ['shipped', 'on_hold', 'cancelled'],
            'shipped' => ['delivered'],
            'delivered' => ['completed'],
            'on_hold' => ['paid', 'cancelled'],
            'cancelled' => [],
            'completed' => [],
        ];

        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }
}