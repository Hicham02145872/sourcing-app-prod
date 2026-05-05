<?php

use App\Models\ShippingCompany;
use App\Services\LarkSheetService;
use Illuminate\Support\Facades\Http;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

// Get the first shipping company with Lark config
$company = ShippingCompany::whereNotNull('lark_app_id')->first();

if (! $company) {
    exit('No company with Lark config found.');
}

echo 'Testing header installation for: '.$company->name."\n";

$service = new LarkSheetService;
$token = $service->getAccessToken($company->lark_app_id, $company->lark_app_secret);

if (! $token) {
    exit("Failed to get access token.\n");
}

$realToken = $service->resolveRealToken($company->lark_base_token, $token);
$targetSheetTitle = $company->lark_table_id ?: 'Sheet1';

// Reflection to call protected methods
$reflector = new ReflectionClass($service);
$resolveSheetId = $reflector->getMethod('resolveSheetId');
$resolveSheetId->setAccessible(true);
$sheetId = $resolveSheetId->invoke($service, $realToken, $token, $targetSheetTitle);

if (! $sheetId) {
    exit("Failed to resolve sheet ID for title: $targetSheetTitle\n");
}

echo "Resolved Sheet ID: $sheetId\n";

// Manual check of what current logic does
$urlGet = "https://open.larksuite.com/open-apis/sheets/v2/spreadsheets/{$realToken}/values/".urlencode("{$sheetId}!A1:Z1");
$getResponse = Http::withToken($token)->get($urlGet);

echo 'GET A1:Z1 status: '.$getResponse->status()."\n";
echo 'GET A1:Z1 response: '.$getResponse->body()."\n";

$headers = ['Order ID', 'Date', 'Status', 'Client Name', 'Product Name', 'Quantity', 'Tracking Number', 'Address', 'Phone', 'Image URL', 'Notes'];

$urlUpdate = "https://open.larksuite.com/open-apis/sheets/v2/spreadsheets/{$realToken}/values";
$payload = [
    'valueRange' => [
        'range' => "{$sheetId}!A1:K1",
        'values' => [$headers],
    ],
];

echo "Sending PUT to $urlUpdate with range {$sheetId}!A1:K1\n";
$putResponse = Http::withToken($token)->put($urlUpdate, $payload);

echo 'PUT status: '.$putResponse->status()."\n";
echo 'PUT body: '.$putResponse->body()."\n";
