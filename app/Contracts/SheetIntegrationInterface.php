<?php

namespace App\Contracts;

use App\Models\ShippingCompany;
use App\Models\SourcingOrder;
use Illuminate\Support\Collection;

interface SheetIntegrationInterface
{
    /**
     * Test the connection to the integration.
     */
    public function testConnection(array $config): array;

    /**
     * Ensure headers and formatting are in place.
     */
    public function ensureHeaders(ShippingCompany $company): array;

    /**
     * Sync a single order to the sheet.
     */
    public function syncOrder(SourcingOrder $order, ShippingCompany $company): bool;

    /**
     * Sync only the given destinations of an order to the given company's sheet.
     * Used when an order has multiple destinations, each assigned to a different shipping company.
     *
     * @param  Collection<int, \App\Models\SourcingRequestDestination>  $destinations
     */
    public function syncOrderDestinations(SourcingOrder $order, ShippingCompany $company, Collection $destinations): bool;

    /**
     * Update the status of an existing order row.
     */
    public function updateOrderStatus(SourcingOrder $order, string $newStatus): bool;

    /**
     * Batch sync multiple orders.
     */
    public function batchSync(array $orders, ShippingCompany $company): array;
}
