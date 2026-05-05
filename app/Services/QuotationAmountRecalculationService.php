<?php

namespace App\Services;

use App\Models\Quotation;
use App\Models\SourcingRequestDestination;

class QuotationAmountRecalculationService
{
    /**
     * Recalcule amount selon la quantité totale actuelle.
     *
     * - Sous-total : unit_price × nouvelle quantité (inchangé logiquement).
     * - Frais d'expédition Chine (delivery_cost_china) : proratisés comme à la création du devis
     *   (montant saisi pour l'ancienne quantité → même ratio que la quantité totale).
     * - Commission : inchangée (forfait).
     * - estimated_product_cost : proratisé si présent (coût produit total).
     *
     * @param  int  $previousTotalQuantity  Somme des quantités avant la mise à jour des destinations.
     */
    public function recalculate(Quotation $quotation, int $previousTotalQuantity): void
    {
        $newTotalQty = (int) SourcingRequestDestination::query()
            ->where('sourcing_request_id', $quotation->sourcing_request_id)
            ->sum('quantity');

        $delivery = (float) $quotation->delivery_cost_china;
        if ($previousTotalQuantity > 0) {
            $delivery = round($delivery * ($newTotalQty / $previousTotalQuantity), 2);
            $quotation->delivery_cost_china = $delivery;
        }

        $subtotal = (float) $quotation->unit_price * $newTotalQty;
        $amount = $subtotal + (float) $quotation->commission_service + $delivery;

        $quotation->amount = round($amount, 2);

        if ($quotation->estimated_product_cost !== null && $previousTotalQuantity > 0) {
            $unitProductCost = (float) $quotation->estimated_product_cost / $previousTotalQuantity;
            $quotation->estimated_product_cost = round($unitProductCost * $newTotalQty, 2);
        }

        $quotation->save();
    }
}
