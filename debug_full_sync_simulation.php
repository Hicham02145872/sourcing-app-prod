<?php

use App\Models\SourcingOrder;
use App\Services\LarkSheetService;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get latest order or specific ID if provided
$orderId = 26; // Defaulting to the one from logs, but code below will try to find latest
$latest = SourcingOrder::latest()->first();
if ($latest) {
    $orderId = $latest->id;
}

echo "Simulating Sync for Order #$orderId\n";

$order = SourcingOrder::find($orderId);
if (! $order) {
    echo "Order not found!\n";
    exit;
}

$order->load('shippingCompany');
$company = $order->shippingCompany;

if (! $company) {
    echo "Shipping Company not found for order.\n";
    exit;
}

echo 'Order Status: '.$order->status."\n";
echo 'Company: '.$company->name."\n";
echo 'Lark Token: '.$company->lark_base_token."\n";

// Force status to shipment_preparing for simulation context (though service doesn't check it, listener does)
// $order->status = 'shipment_preparing';

$service = new LarkSheetService;

echo "Calling syncOrder...\n";
try {
    $result = $service->syncOrder($order, $company);
    if ($result) {
        echo "SUCCESS: Sync returned true.\n";
    } else {
        echo "FAILURE: Sync returned false.\n";
    }
} catch (\Exception $e) {
    echo 'EXCEPTION: '.$e->getMessage()."\n";
    echo $e->getTraceAsString();
}
