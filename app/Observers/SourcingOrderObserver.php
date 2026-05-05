<?php

namespace App\Observers;

use App\Models\SourcingOrder;

class SourcingOrderObserver
{
    /**
     * Handle the SourcingOrder "saving" event.
     *
     * @return void
     */
    public function saving(SourcingOrder $sourcingOrder)
    {
        // Calculate Net Profit only if we have at least partial data
        // Logic: Net Profit = Total Sales - (Product Cost + Shipping + Loss)
        // If a cost is NULL, we treat it as 0 for the calculation, OR we leave profit as NULL until fully costed?
        // User Requirement: "Recalculate automatically", "Staged Entry".
        // Let's assume if ALL costs are null, profit is null. If ANY cost is present, we calculate.

        $sales = $sourcingOrder->total_amount ?? 0;
        $productCost = $sourcingOrder->product_cost_price ?? 0;
        $shipping = $sourcingOrder->shipping_cost_real ?? 0;
        $loss = $sourcingOrder->rejection_loss_cost ?? 0;
        $refund = $sourcingOrder->refund_amount ?? 0;

        // If specific costs are missing (NULL), it's debatable if we should show a "Profit".
        // Use case: user enters product cost but not shipping yet.
        // If we calculate: Profit = Sales - Cost. This might be misleadingly high.
        // However, the user said "Can be NULL initially".
        // Let's calculate it anyway, but maybe the UI handles the interpretation.
        // OR better: if product_cost is set, we calculate.

        if (! is_null($sourcingOrder->product_cost_price)) {
            $sourcingOrder->net_profit_or_loss = $sales - ($productCost + $shipping + $loss + $refund);
        } else {
            // If we don't even know the product cost, profit is undefined.
            $sourcingOrder->net_profit_or_loss = null;
        }
    }
}
