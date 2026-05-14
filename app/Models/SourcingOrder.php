<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SourcingOrder extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::deleting(function ($sourcingOrder) {
            if ($sourcingOrder->proof_of_payment_path) {
                Storage::disk('public')->delete($sourcingOrder->proof_of_payment_path);
            }
            if ($sourcingOrder->refund_proof_path) {
                Storage::disk('public')->delete($sourcingOrder->refund_proof_path);
            }
            // Delete associated media records (which will trigger their own deleting events for physical files)
            $sourcingOrder->media()->each(function ($media) {
                $media->delete();
            });
        });
    }

    public const STATUSES = [
        'pending_payment',
        'paid',
        'shipment_preparing',
        'in_transit_china',
        'arrival_uae',
        'customs_clearance_uae',
        'in_transit_uae',
        'arrival_destination_country',
        'customs_clearance_destination_country',
        'out_for_delivery',
        'delivered',
        'delivery_failed',
        'shipment_delayed',
        'shipment_returned',
        'shipment_canceled',
        'shipment_canceled',
        'order_completed',
        'refunded',
        'waiting_for_refund',
        'refund_approved',
        'refund_rejected',
    ];

    protected $fillable = [
        'user_id',
        'quotation_id',
        'sourcing_request_id',
        'total_amount',
        'status',
        'rejection_reason',
        'proof_of_payment_path',
        'tracking_number',
        'tracking_carrier',
        'product_cost_price',
        'shipping_cost_real',
        'rejection_loss_cost',
        'net_profit_or_loss',
        // Initial estimates for variance tracking
        'initial_estimated_product_cost',
        'initial_estimated_shipping_cost',
        'initial_estimated_other_costs',
        'cost_adjustment_notes',
        'assigned_to_admin_id',
        'refund_amount',
        'shipping_company_id',
        'sheet_synced_at',
        'sheet_sync_error',
        'fsb_tracking_created_at',
        'real_tracking_assigned_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'product_cost_price',
        'shipping_cost_real',
        'rejection_loss_cost',
        'tracking_carrier',
        'net_profit_or_loss',
        'initial_estimated_product_cost',
        'initial_estimated_shipping_cost',
        'initial_estimated_other_costs',
        'real_tracking_assigned_at', // Champ interne uniquement, non exposé au client
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'product_cost_price' => 'decimal:2',
        'shipping_cost_real' => 'decimal:2',
        'rejection_loss_cost' => 'decimal:2',
        'net_profit_or_loss' => 'decimal:2',
        'initial_estimated_product_cost' => 'decimal:2',
        'initial_estimated_shipping_cost' => 'decimal:2',
        'initial_estimated_other_costs' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'fsb_tracking_created_at' => 'datetime',
        'real_tracking_assigned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function sourcingRequest()
    {
        return $this->belongsTo(SourcingRequest::class);
    }

    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_to_admin_id');
    }

    public function shippingCompany()
    {
        return $this->belongsTo(ShippingCompany::class);
    }

    /**
     * Per-destination tracking/shipping when order has multiple destinations.
     */
    public function destinationShipments()
    {
        return $this->hasMany(SourcingOrderDestinationShipment::class);
    }

    /**
     * Whether this order has more than one destination (so we use per-destination tracking).
     */
    public function hasMultipleDestinations(): bool
    {
        $count = $this->quotation?->sourcingRequest?->destinations?->count() ?? 0;

        return $count > 1;
    }

    /**
     * Get tracking number for a destination. Uses destination_shipments when order has multiple destinations, else order-level.
     */
    public function getTrackingNumberForDestination(?int $sourcingRequestDestinationId): ?string
    {
        if ($this->hasMultipleDestinations() && $sourcingRequestDestinationId) {
            $shipment = $this->relationLoaded('destinationShipments')
                ? $this->destinationShipments->firstWhere('sourcing_request_destination_id', $sourcingRequestDestinationId)
                : $this->destinationShipments()->where('sourcing_request_destination_id', $sourcingRequestDestinationId)->first();

            return $shipment?->tracking_number;
        }

        return $this->tracking_number;
    }

    /**
     * Get shipping company for a destination. Uses destination_shipments when order has multiple destinations, else order-level.
     */
    public function getShippingCompanyForDestination(?int $sourcingRequestDestinationId): ?ShippingCompany
    {
        if ($this->hasMultipleDestinations() && $sourcingRequestDestinationId) {
            $shipment = $this->relationLoaded('destinationShipments')
                ? $this->destinationShipments->firstWhere('sourcing_request_destination_id', $sourcingRequestDestinationId)
                : $this->destinationShipments()->with('shippingCompany')->where('sourcing_request_destination_id', $sourcingRequestDestinationId)->first();

            return $shipment?->shippingCompany;
        }

        return $this->shippingCompany;
    }

    public function canTransitionTo(string $newStatus): bool
    {
        // Admins can skip statuses and move to any defined status (non-transactional)
        if (auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())) {
            return in_array($newStatus, self::STATUSES) || $newStatus === 'on_hold';
        }

        $allowedTransitions = [
            'pending_payment' => ['paid', 'shipment_canceled'],
            'paid' => ['shipment_preparing', 'on_hold', 'shipment_canceled'],
            'shipment_preparing' => ['in_transit_china', 'shipment_delayed', 'shipment_canceled'],
            'in_transit_china' => ['arrival_uae', 'shipment_delayed', 'shipment_canceled'],
            'arrival_uae' => ['customs_clearance_uae', 'shipment_delayed', 'shipment_canceled'],
            'customs_clearance_uae' => ['in_transit_uae', 'shipment_delayed', 'shipment_canceled'],
            'in_transit_uae' => ['arrival_destination_country', 'shipment_delayed', 'shipment_canceled'],
            'arrival_destination_country' => ['customs_clearance_destination_country', 'shipment_delayed', 'shipment_canceled'],
            'customs_clearance_destination_country' => ['out_for_delivery', 'shipment_delayed', 'shipment_canceled'],
            'out_for_delivery' => ['delivered', 'delivery_failed', 'shipment_delayed', 'shipment_returned', 'shipment_canceled'],
            'delivered' => ['order_completed'],
            'delivery_failed' => ['shipment_returned', 'shipment_canceled'],
            'shipment_delayed' => ['shipment_preparing', 'in_transit_china', 'arrival_uae', 'in_transit_uae', 'arrival_destination_country', 'out_for_delivery', 'shipment_canceled'],
            'shipment_returned' => ['shipment_canceled'],
            'shipment_canceled' => [],
            'order_completed' => ['refunded'],
            'on_hold' => ['paid', 'shipment_canceled', 'refunded'],
            'refunded' => [],
        ];

        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }

    /**
     * Whether the order can transition to the given status when the update is from tracking (cron).
     * Allows any "forward" progression in the shipping chain (e.g. shipment_preparing → arrival_uae
     * when tracking shows the package already in Dubai).
     */
    public function canTransitionToFromTracking(string $newStatus): bool
    {
        if (! in_array($newStatus, self::STATUSES)) {
            return false;
        }

        $shippingChainOrder = [
            'shipment_preparing' => 1,
            'in_transit_china' => 2,
            'arrival_uae' => 3,
            'customs_clearance_uae' => 4,
            'in_transit_uae' => 5,
            'arrival_destination_country' => 6,
            'customs_clearance_destination_country' => 7,
            'out_for_delivery' => 8,
            'delivered' => 9,
            'order_completed' => 10,
        ];

        $currentOrder = $shippingChainOrder[$this->status] ?? 0;
        $newOrder = $shippingChainOrder[$newStatus] ?? 0;

        if ($newOrder === 0) {
            return false;
        }

        return $newOrder >= $currentOrder;
    }

    /**
     * Calculate variance between estimated and actual costs
     */
    public function getCostVariance(): ?array
    {
        if (is_null($this->initial_estimated_product_cost)) {
            return null;
        }

        $estimatedTotal = ($this->initial_estimated_product_cost ?? 0)
                        + ($this->initial_estimated_shipping_cost ?? 0)
                        + ($this->initial_estimated_other_costs ?? 0);

        $actualTotal = ($this->product_cost_price ?? 0)
                     + ($this->shipping_cost_real ?? 0)
                     + ($this->rejection_loss_cost ?? 0)
                     + ($this->refund_amount ?? 0);

        $variance = $actualTotal - $estimatedTotal;
        $variancePercentage = $estimatedTotal > 0
            ? ($variance / $estimatedTotal) * 100
            : 0;

        return [
            'estimated_total' => $estimatedTotal,
            'actual_total' => $actualTotal,
            'variance' => $variance,
            'variance_percentage' => $variancePercentage,
            'product_variance' => ($this->product_cost_price ?? 0) - ($this->initial_estimated_product_cost ?? 0),
            'shipping_variance' => ($this->shipping_cost_real ?? 0) - ($this->initial_estimated_shipping_cost ?? 0),
        ];
    }

    public function media()
    {
        return $this->hasMany(SourcingOrderMedia::class);
    }

    public function refundRequests()
    {
        return $this->hasMany(RefundRequest::class);
    }

    public function toGoogleSheetArray(): array
    {
        return [
            'id' => $this->display_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'client_name' => $this->user->name,
            'client_email' => $this->user->email,
            'product_name' => $this->quotation->sourcingRequest->product_name,
            'quantity' => $this->quotation->sourcingRequest->destinations->sum('quantity'),
            'total_amount' => $this->total_amount,
            'currency' => $this->quotation->currency,
            'shipping_method' => $this->quotation->sourcingRequest->shipping_method,
            'tracking_number' => $this->tracking_number,
            'admin_assigned' => $this->assignedAdmin?->name ?? 'N/A',
            'net_profit' => $this->net_profit_or_loss,
            'product_image' => $this->quotation->sourcingRequest->product_image ? asset('storage/'.$this->quotation->sourcingRequest->product_image) : '',
        ];
    }

    /**
     * Get the custom display ID (multiple of 5).
     */
    public function getDisplayIdAttribute(): int
    {
        return $this->id * 5;
    }

    public function toShippingCompanySheetArray(): array
    {
        return [
            'id' => $this->display_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'product_name' => $this->quotation->sourcingRequest->product_name,
            'quantity' => $this->quotation->sourcingRequest->destinations->sum('quantity'),
            'tracking_number' => $this->tracking_number,
            'client_name' => $this->user->name,
            'address' => $this->quotation->sourcingRequest->address ?? 'N/A', // Using address from sourcing request
            'phone' => $this->quotation->sourcingRequest->phone_number ?? 'N/A',
            'product_image' => $this->quotation->sourcingRequest->product_image ? '=IMAGE("'.asset('storage/'.$this->quotation->sourcingRequest->product_image).'")' : '',
            'weight' => '', // Placeholder
            'notes' => '', // Placeholder
        ];
    }

    /**
     * Get the status as seen by the client (masking in_transit_china).
     */
    public function getClientStatusAttribute(): string
    {
        // Mask China to UAE leg statuses (external tracking leg)
        if (in_array($this->status, ['in_transit_china', 'arrival_uae', 'customs_clearance_uae'])) {
            return 'shipment_preparing';
        }

        return $this->status;
    }

    /**
     * Get the translated client status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return __($this->client_status);
    }

    /**
     * Get the FSB tracking number alias (main order).
     */
    public function getFsbTrackingNumberAttribute(): string
    {
        return 'FSB'.str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get the FSB tracking number for a destination index (0-based).
     * First destination = FSB000010, second = FSB000011 for order id 10.
     */
    public function getFsbTrackingNumberForDestinationIndex(int $index): string
    {
        $numeric = $this->id + $index;

        return 'FSB'.str_pad((string) $numeric, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Resolve an FSB number (e.g. FSB000011) to the corresponding order.
     * Per-destination: 11 = order 10 dest 1. We try (order_id + index) first so multi-dest orders win.
     */
    public static function resolveFsbNumberToOrder(string $fsbNumber): ?self
    {
        $resolved = self::resolveFsbNumberToOrderAndDestinationIndex($fsbNumber);

        return $resolved['order'] ?? null;
    }

    /**
     * Resolve FSB to order and destination index (0-based) for per-destination tracking.
     *
     * @return array{order: self, destination_index: int}|null
     */
    public static function resolveFsbNumberToOrderAndDestinationIndex(string $fsbNumber): ?array
    {
        $numeric = (int) substr($fsbNumber, 3);

        for ($index = 1; $index <= $numeric; $index++) {
            $orderId = $numeric - $index;
            $order = self::with('quotation.sourcingRequest.destinations')->find($orderId);
            if ($order && $order->quotation?->sourcingRequest?->destinations?->count() > $index) {
                return ['order' => $order, 'destination_index' => $index];
            }
        }

        $order = self::find($numeric);
        if ($order) {
            return ['order' => $order, 'destination_index' => 0];
        }

        return null;
    }

    /**
     * Whether this destination (by index) has a real tracking number assigned.
     */
    public function hasRealTrackingForDestinationIndex(int $destinationIndex): bool
    {
        $destinations = $this->quotation?->sourcingRequest?->destinations;
        if (! $destinations || ! $destinations->has($destinationIndex)) {
            return $this->hasRealTracking();
        }
        $destId = $destinations->get($destinationIndex)->id;

        return ! empty(trim((string) $this->getTrackingNumberForDestination($destId)));
    }

    /**
     * Get the total quantity of the order.
     */
    public function getTotalQuantityAttribute(): int
    {
        return $this->quotation->sourcingRequest->destinations->sum('quantity');
    }

    /**
     * Check if a real tracking number has been assigned.
     */
    public function hasRealTracking(): bool
    {
        return !is_null($this->tracking_number) && !is_null($this->real_tracking_assigned_at);
    }

    /**
     * Determine if virtual tracking status should be used.
     */
    public function shouldUseVirtualStatus(): bool
    {
        // Use virtual status if FSB tracking was created but no real tracking assigned yet
        return !is_null($this->fsb_tracking_created_at) && !$this->hasRealTracking();
    }

    /**
     * Get virtual tracking status based on time elapsed since FSB creation.
     */
    public function getVirtualTrackingStatus(): string
    {
        if (!$this->fsb_tracking_created_at) {
            return 'pending_payment';
        }

        $hoursSinceCreation = now()->diffInHours($this->fsb_tracking_created_at);

        if ($hoursSinceCreation < 24) {
            return 'shipment_preparing';
        }

        return 'in_transit_china';
    }
}
