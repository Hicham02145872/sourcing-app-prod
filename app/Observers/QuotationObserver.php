<?php

namespace App\Observers;

use App\Models\Quotation;

class QuotationObserver
{
    /**
     * Handle the Quotation "saving" event.
     * Automatically calculate estimated_net_profit when cost estimates are provided
     */
    public function saving(Quotation $quotation): void
    {
        // Recalculate estimated profit if cost estimates are provided
        if (! is_null($quotation->estimated_product_cost)) {
            $quotation->estimated_net_profit = $quotation->calculateEstimatedProfit();
        }
    }
}
