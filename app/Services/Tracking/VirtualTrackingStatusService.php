<?php

namespace App\Services\Tracking;

use App\Models\SourcingOrder;
use Illuminate\Support\Facades\Log;

class VirtualTrackingStatusService
{
    /**
     * Get virtual tracking status for an order.
     */
    public function getVirtualStatus(SourcingOrder $order): string
    {
        if (!$order->fsb_tracking_created_at) {
            return 'pending_payment';
        }

        $hoursSinceCreation = now()->diffInHours($order->fsb_tracking_created_at);

        if ($hoursSinceCreation < 24) {
            return 'shipment_preparing';
        }

        return 'in_transit_china';
    }

    /**
     * Determine if virtual status should be used.
     */
    public function shouldUseVirtualStatus(SourcingOrder $order): bool
    {
        return $order->shouldUseVirtualStatus();
    }

    /**
     * Get virtual tracking response for API.
     */
    public function getVirtualTrackingResponse(SourcingOrder $order): array
    {
        $virtualStatus = $this->getVirtualStatus($order);
        
        $statusTexts = [
            'pending_payment' => __('Pending Payment'),
            'shipment_preparing' => __('Shipping Preparing'),
            'in_transit_china' => __('In Transit China'),
        ];

        $statusDescriptions = [
            'pending_payment' => __('Order is pending payment confirmation'),
            'shipment_preparing' => __('Your order is being prepared for shipment'),
            'in_transit_china' => __('Your order is in transit from China'),
        ];

        $events = [];
        if ($virtualStatus === 'shipment_preparing') {
            $events[] = [
                'date' => $order->fsb_tracking_created_at->toIso8601String(),
                'status' => 'shipment_preparing',
                'description' => __('Order confirmed and being prepared for shipment'),
                'location' => __('China'),
            ];
        } elseif ($virtualStatus === 'in_transit_china') {
            $events[] = [
                'date' => $order->fsb_tracking_created_at->toIso8601String(),
                'status' => 'shipment_preparing',
                'description' => __('Order confirmed and being prepared for shipment'),
                'location' => __('China'),
            ];
            $events[] = [
                'date' => $order->fsb_tracking_created_at->copy()->addHours(24)->toIso8601String(),
                'status' => 'in_transit_china',
                'description' => __('Order is in transit from China'),
                'location' => __('China'),
            ];
        }

        return [
            'success' => true,
            'tracking_number' => $order->fsb_tracking_number,
            'status' => $virtualStatus,
            'status_text' => $statusTexts[$virtualStatus] ?? $virtualStatus,
            'current_status' => $statusTexts[$virtualStatus] ?? $virtualStatus,
            'is_virtual' => true,
            'message' => $statusDescriptions[$virtualStatus] ?? __('Tracking information will be updated soon'),
            'events' => $events,
            'provider' => 'FSB',
            'last_updated_at' => now()->toIso8601String(),
            'source' => 'virtual',
        ];
    }
}
