<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->boot();

app()->setLocale('ar');
echo 'Locale: '.app()->getLocale()."\n";
echo 'Update: Shipment Preparing => '.__('Update: Shipment Preparing')."\n";
echo 'Your order #:orderId is now :status. => '.__('Your order #:orderId is now :status.', ['orderId' => 15, 'status' => 'Paid'])."\n";
