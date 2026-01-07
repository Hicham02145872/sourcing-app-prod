<?php

namespace App\Contracts;

use App\Models\SourcingOrder;
use App\Models\ShippingCompany;

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
     * Update the status of an existing order row.
     */
    public function updateOrderStatus(SourcingOrder $order, string $newStatus): bool;

    /**
     * Batch sync multiple orders.
     */
    public function batchSync(array $orders, ShippingCompany $company): array;
}
