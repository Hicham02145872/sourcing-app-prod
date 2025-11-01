<?php

namespace App\Services;

use App\Models\User;
use App\Models\SourcingRequest;
use App\Models\Quotation;
use App\Models\SourcingOrder;

class TimelineService
{
    /**
     * Generate the timeline for a given user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Support\Collection
     */
    public function generateTimeline(User $user)
    {
        $timeline = [];

        $requests = $user->sourcingRequests()
                         ->with(['quotation.order.user'])
                         ->get();

        foreach ($requests as $request) {
            // Event: Sourcing Request created
            $timeline[] = [
                'date' => $request->created_at,
                'type' => 'request_created',
                'title' => __('Sourcing request created'),
                'description' => __('You created a request for :product.', ['product' => $request->product_name]),
                'link' => route('client.sourcing-requests.show', $request),
                'icon' => 'plus-circle'
            ];

            if ($request->quotation) {
                $quotation = $request->quotation;
                // Event: Quotation received
                $timeline[] = [
                    'date' => $quotation->created_at,
                    'type' => 'quotation_received',
                    'title' => __('Quotation received'),
                    'description' => __('A quotation of :amount :currency was received for :product.', [
                        'amount' => $quotation->amount,
                        'currency' => $quotation->currency,
                        'product' => $request->product_name,
                    ]),
                    'link' => route('client.sourcing-requests.show', $request),
                    'icon' => 'cash'
                ];

                if ($quotation->status === 'accepted') {
                    $timeline[] = [
                        'date' => $quotation->updated_at, // Use updated_at for status changes
                        'type' => 'quotation_accepted',
                        'title' => __('Quotation accepted'),
                        'description' => __('You accepted the quotation for :product.', ['product' => $request->product_name]),
                        'link' => route('client.sourcing-requests.show', $request),
                        'icon' => 'check-circle'
                    ];
                }

                if ($quotation->order) {
                    $order = $quotation->order;
                    // Event: Order created (payment pending)
                    $timeline[] = [
                        'date' => $order->created_at,
                        'type' => 'order_created',
                        'title' => __('Order created'),
                        'description' => __('Your order for :product has been created and is pending payment.', ['product' => $request->product_name]),
                        'link' => route('client.sourcing-orders.show', $order),
                        'icon' => 'shopping-cart'
                    ];

                    // Track status changes by looking at the updated_at timestamp
                    if ($order->status !== 'pending_payment' && $order->updated_at->gt($order->created_at)) {
                        $timeline[] = [
                           'date' => $order->updated_at,
                           'type' => 'order_updated',
                           'title' => __('Order status updated'),
                           'description' => __('The status of your order for :product is now: :status', [
                               'product' => $request->product_name,
                               'status' => __(ucfirst(str_replace('_', ' ', $order->status)))
                           ]),
                           'link' => route('client.sourcing-orders.show', $order),
                           'icon' => 'truck'
                       ];
                    }
                }
            }
        }

        // Sort the timeline by date, descending
        $sortedTimeline = collect($timeline)->sortByDesc('date');

        return $sortedTimeline;
    }
}
