<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SourcingOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShipmentCalendarController extends Controller
{
    public function index()
    {
        // Get all orders with tracking information
        $orders = SourcingOrder::with(['user', 'quotation.sourcingRequest', 'assignedAdmin'])
            ->whereNotIn('status', ['pending_payment', 'shipment_canceled', 'refunded'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.shipment-calendar.index', compact('orders'));
    }

    public function getEvents(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $orders = SourcingOrder::with(['user', 'quotation.sourcingRequest'])
            ->whereNotIn('status', ['pending_payment', 'shipment_canceled', 'refunded'])
            ->get();

        $events = [];

        foreach ($orders as $order) {
            // Create timeline events for each shipment stage
            $events = array_merge($events, $this->createShipmentEvents($order));
        }

        return response()->json($events);
    }

    private function createShipmentEvents(SourcingOrder $order)
    {
        $events = [];
        $baseDate = $order->created_at;

        // Get product information
        $productName = $order->quotation->sourcingRequest->product_name ?? 'N/A';
        $productImage = $order->quotation->sourcingRequest->product_image ?? null;
        $sourcingLocation = $order->quotation->sourcingRequest->sourcing_location ?? 'China';

        // Determine if sourcing from Dubai/UAE or China
        $isDubaiSource = stripos($sourcingLocation, 'dubai') !== false ||
                         stripos($sourcingLocation, 'uae') !== false ||
                         stripos($sourcingLocation, 'emirates') !== false;

        if ($isDubaiSource) {
            // Dubai/UAE Timeline: 1-7 working days
            $stages = [
                [
                    'title' => '📦 Préparation Dubai',
                    'status' => 'shipment_preparing',
                    'days_offset' => 0,
                    'duration' => 2, // Preparation: 2 days
                    'color' => '#f97316', // Orange
                    'type' => 'departure',
                ],
                [
                    'title' => '🚚 Transit local',
                    'status' => 'in_transit_uae',
                    'days_offset' => 2,
                    'duration' => 2, // Local transit: 2 days
                    'color' => '#06b6d4', // Cyan
                    'type' => 'transit',
                ],
                [
                    'title' => '🏛️ Dédouanement',
                    'status' => 'customs_clearance_destination_country',
                    'days_offset' => 4,
                    'duration' => 2, // Customs: 2 days
                    'color' => '#8b5cf6', // Purple
                    'type' => 'customs',
                ],
                [
                    'title' => '🎯 Livraison finale',
                    'status' => 'delivered',
                    'days_offset' => 6,
                    'duration' => 1, // Final delivery: 1 day
                    'color' => '#10b981', // Green
                    'type' => 'delivery',
                ],
            ];
        } else {
            // China Timeline: 15-20 working days
            $stages = [
                [
                    'title' => '📦 Départ Chine',
                    'status' => 'shipment_preparing',
                    'days_offset' => 0,
                    'duration' => 3, // Preparation: 3 days
                    'color' => '#f97316', // Orange
                    'type' => 'departure',
                ],
                [
                    'title' => '🚢 Transit Chine → EAU',
                    'status' => 'in_transit_china',
                    'days_offset' => 3,
                    'duration' => 12, // Sea/Air freight: 12 days
                    'color' => '#0ea5e9', // Sky blue
                    'type' => 'transit',
                ],
                [
                    'title' => '✈️ Arrivée EAU',
                    'status' => 'arrival_uae',
                    'days_offset' => 15,
                    'duration' => 1, // Arrival processing: 1 day
                    'color' => '#3b82f6', // Blue
                    'type' => 'arrival',
                ],
                [
                    'title' => '🏛️ Dédouanement EAU',
                    'status' => 'customs_clearance_uae',
                    'days_offset' => 16,
                    'duration' => 2, // Customs clearance: 2 days
                    'color' => '#8b5cf6', // Purple
                    'type' => 'customs',
                ],
                [
                    'title' => '🚚 Transit vers destination',
                    'status' => 'in_transit_uae',
                    'days_offset' => 18,
                    'duration' => 3, // Local transit: 3 days
                    'color' => '#06b6d4', // Cyan
                    'type' => 'transit',
                ],
                [
                    'title' => '🏛️ Dédouanement final',
                    'status' => 'customs_clearance_destination_country',
                    'days_offset' => 21,
                    'duration' => 2, // Final customs: 2 days
                    'color' => '#8b5cf6', // Purple
                    'type' => 'customs',
                ],
                [
                    'title' => '🎯 Livraison finale',
                    'status' => 'delivered',
                    'days_offset' => 23,
                    'duration' => 2, // Final delivery: 2 days
                    'color' => '#10b981', // Green
                    'type' => 'delivery',
                ],
            ];
        }

        foreach ($stages as $stage) {
            $startDate = Carbon::parse($baseDate)->addDays($stage['days_offset']);
            $endDate = Carbon::parse($startDate)->addDays($stage['duration']);

            // Determine if this stage is completed, current, or upcoming
            $isCompleted = $this->isStageCompleted($order->status, $stage['status']);
            $isCurrent = $order->status === $stage['status'];

            $events[] = [
                'id' => $order->id.'-'.$stage['status'],
                'title' => $stage['title'].' - '.$productName,
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
                'backgroundColor' => $isCompleted ? $stage['color'] : ($isCurrent ? $stage['color'] : $stage['color'].'40'),
                'borderColor' => $stage['color'],
                'textColor' => $isCompleted || $isCurrent ? '#ffffff' : '#64748b',
                'extendedProps' => [
                    'orderId' => $order->id,
                    'displayId' => $order->display_id,
                    'clientName' => $order->user->name ?? 'N/A',
                    'status' => $order->status,
                    'trackingNumber' => $order->tracking_number ?? 'N/A',
                    'stage' => $stage['status'],
                    'type' => $stage['type'],
                    'isCompleted' => $isCompleted,
                    'isCurrent' => $isCurrent,
                    'productName' => $productName,
                    'productImage' => $productImage ? asset('storage/'.$productImage) : null,
                    'sourcingLocation' => $sourcingLocation,
                ],
                'classNames' => [
                    $isCompleted ? 'completed-stage' : ($isCurrent ? 'current-stage' : 'upcoming-stage'),
                ],
            ];
        }

        return $events;
    }

    private function isStageCompleted($currentStatus, $stageStatus)
    {
        $statusOrder = [
            'pending_payment' => 0,
            'paid' => 1,
            'shipment_preparing' => 2,
            'in_transit_china' => 3,
            'arrival_uae' => 4,
            'customs_clearance_uae' => 5,
            'in_transit_uae' => 6,
            'arrival_destination_country' => 7,
            'customs_clearance_destination_country' => 8,
            'out_for_delivery' => 9,
            'delivered' => 10,
            'order_completed' => 11,
        ];

        $currentOrder = $statusOrder[$currentStatus] ?? 0;
        $stageOrder = $statusOrder[$stageStatus] ?? 0;

        return $currentOrder > $stageOrder;
    }
}
