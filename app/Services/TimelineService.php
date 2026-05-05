<?php

namespace App\Services;

use App\Models\Quotation;
use App\Models\User;

class TimelineService
{
    /**
     * Generate the timeline for a given user.
     *
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
                'icon' => 'plus-circle',
            ];

            // Add events for terminal statuses of the sourcing request itself
            if ($request->status === 'rejected') {
                $timeline[] = [
                    'date' => $request->updated_at,
                    'type' => 'request_rejected',
                    'title' => __('Sourcing Request Rejected'),
                    'description' => __('Your sourcing request for :product has been rejected.', ['product' => $request->product_name]),
                    'link' => route('client.sourcing-requests.show', $request),
                    'icon' => 'x-circle',
                ];
            } elseif ($request->status === 'cancelled') {
                $timeline[] = [
                    'date' => $request->updated_at,
                    'type' => 'request_cancelled',
                    'title' => __('Sourcing Request Cancelled'),
                    'description' => __('Your sourcing request for :product has been cancelled.', ['product' => $request->product_name]),
                    'link' => route('client.sourcing-requests.show', $request),
                    'icon' => 'ban',
                ];
            } elseif ($request->status === 'completed') {
                $timeline[] = [
                    'date' => $request->updated_at,
                    'type' => 'request_completed',
                    'title' => __('Sourcing Request Completed'),
                    'description' => __('Your sourcing request for :product has been completed.', ['product' => $request->product_name]),
                    'link' => route('client.sourcing-requests.show', $request),
                    'icon' => 'check-circle',
                ];
            }

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
                    'icon' => 'cash',
                ];

                if ($quotation->status === 'accepted') {
                    $timeline[] = [
                        'date' => $quotation->updated_at, // Use updated_at for status changes
                        'type' => 'quotation_accepted',
                        'title' => __('Quotation accepted'),
                        'description' => __('You accepted the quotation for :product.', ['product' => $request->product_name]),
                        'link' => route('client.sourcing-requests.show', $request),
                        'icon' => 'check-circle',
                    ];
                } elseif ($quotation->status === 'rejected') {
                    $timeline[] = [
                        'date' => $quotation->updated_at,
                        'type' => 'quotation_rejected',
                        'title' => __('Quotation Rejected'),
                        'description' => __('You rejected the quotation for :product.', ['product' => $request->product_name]),
                        'link' => route('client.sourcing-requests.show', $request),
                        'icon' => 'x-circle',
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
                        'icon' => 'shopping-cart',
                    ];

                    // Track status changes by looking at the updated_at timestamp
                    if ($order->status !== 'pending_payment' && $order->updated_at->gt($order->created_at)) {
                        $timeline[] = [
                            'date' => $order->updated_at,
                            'type' => 'order_updated',
                            'title' => __('Order status updated'),
                            'description' => __('The status of your order for :product is now: :status', [
                                'product' => $request->product_name,
                                'status' => __(ucfirst(str_replace('_', ' ', $order->status))),
                            ]),
                            'link' => route('client.sourcing-orders.show', $order),
                            'icon' => 'truck',
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
