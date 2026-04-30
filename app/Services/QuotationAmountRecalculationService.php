<?php

namespace App\Services;

use App\Models\Quotation;
use App\Models\SourcingRequestDestination;

class QuotationAmountRecalculationService
{
    /**
     * Recalcule amount (et estimated_product_cost si présent, proportionnel à la quantité totale)
     * selon la somme actuelle des quantités des destinations.
     *
     * @param  int  $previousTotalQuantity  Somme des quantités avant la mise à jour des destinations (pour prorata du coût produit estimé).
     */
    public function recalculate(Quotation $quotation, int $previousTotalQuantity): void
    {
        $newTotalQty = (int) SourcingRequestDestination::query()
            ->where('sourcing_request_id', $quotation->sourcing_request_id)
            ->sum('quantity');

        $subtotal = (float) $quotation->unit_price * $newTotalQty;
        $amount = $subtotal + (float) $quotation->commission_service + (float) $quotation->delivery_cost_china;

        $quotation->amount = round($amount, 2);

        if ($quotation->estimated_product_cost !== null && $previousTotalQuantity > 0) {
            $unitProductCost = (float) $quotation->estimated_product_cost / $previousTotalQuantity;
            $quotation->estimated_product_cost = round($unitProductCost * $newTotalQty, 2);
        }

        $quotation->save();
    }
}
