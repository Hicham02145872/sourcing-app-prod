<?php

namespace App\Listeners;

use App\Events\QuotationAccepted;
use Illuminate\Support\Facades\Log;

class CopyEstimatesToSourcingOrder
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * Copy cost estimates from quotation to sourcing order
     */
    public function handle(QuotationAccepted $event): void
    {
        $quotation = $event->quotation;
        $order = $quotation->order;

        // Only copy if estimates exist and order exists
        if ($order && ! is_null($quotation->estimated_product_cost)) {
            $order->update([
                'initial_estimated_product_cost' => $quotation->estimated_product_cost,
                'initial_estimated_shipping_cost' => $quotation->estimated_shipping_cost,
                'initial_estimated_other_costs' => $quotation->estimated_other_costs,
                // Initialize real costs with estimates as starting point
                'product_cost_price' => $quotation->estimated_product_cost,
                'shipping_cost_real' => $quotation->estimated_shipping_cost,
                'rejection_loss_cost' => $quotation->estimated_other_costs ?? 0,
            ]);

            Log::info('Copied cost estimates from quotation to order', [
                'quotation_id' => $quotation->id,
                'order_id' => $order->id,
                'estimated_product_cost' => $quotation->estimated_product_cost,
            ]);
        }
    }
}
