<?php

use App\Models\ShippingCompany;
use App\Services\LarkSheetService;
use Illuminate\Support\Facades\Http;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

echo "--- Lark Write Permission Debugger ---\n";

// Use the last company that has Lark credentials
$company = ShippingCompany::whereNotNull('lark_app_id')->latest()->first();
if (! $company) {
    exit("No Lark configuration found in any shipping company.\n");
}

echo 'Testing for Company: '.$company->name."\n";
echo 'Spreadsheet URL: '.$company->lark_base_token."\n";
echo 'Sheet Title: '.($company->lark_table_id ?: 'Sheet1')."\n\n";

$service = new LarkSheetService;
$token = $service->getAccessToken($company->lark_app_id, $company->lark_app_secret);

if (! $token) {
    exit("FAILED: Could not get Access Token.\n");
}
echo "SUCCESS: Got Access Token.\n";

$realToken = $service->resolveRealToken($company->lark_base_token, $token);
echo "Resolved Spreadsheet Token: $realToken\n";

// Step 1: Check Metainfo (Read Permission)
echo "\n[Step 1] Checking Metainfo (Read Permission)...\n";
$urlMeta = "https://open.larksuite.com/open-apis/sheets/v2/spreadsheets/{$realToken}/metainfo";
$resMeta = Http::withToken($token)->get($urlMeta);
echo 'Status: '.$resMeta->status()."\n";
echo 'Body: '.$resMeta->body()."\n";

if ($resMeta->json()['code'] !== 0) {
    exit("FAILED: Insufficient Read Permissions or Invalid Token.\n");
}

$targetTitle = $company->lark_table_id ?: 'Sheet1';
$sheetId = null;
foreach ($resMeta->json()['data']['sheets'] as $sheet) {
    if ($sheet['title'] === $targetTitle) {
        $sheetId = $sheet['sheetId'];
        break;
    }
}
if (! $sheetId) {
    $sheetId = $resMeta->json()['data']['sheets'][0]['sheetId'];
    echo "WARNING: Sheet title '$targetTitle' not found. Using first sheet: ".$resMeta->json()['data']['sheets'][0]['title']." ($sheetId)\n";
} else {
    echo "Using Sheet: $targetTitle ($sheetId)\n";
}

// Step 2: Try Append (Write Permission)
echo "\n[Step 2] Testing Append (Write Permission)...\n";
$urlAppend = "https://open.larksuite.com/open-apis/sheets/v2/spreadsheets/{$realToken}/values_append";
$payload = [
    'valueRange' => [
        'range' => $sheetId,
        'values' => [
            ['DEBUG_TEST', date('Y-m-d H:i:s'), 'TESTING', 'AI Assistant', 'Debug Row', 1, 'N/A', 'N/A', 'N/A', 'N/A', 'Permission Test'],
        ],
    ],
];

echo "Sending POST to values_append...\n";
$resAppend = Http::withToken($token)->post($urlAppend, $payload);
echo 'Status: '.$resAppend->status()."\n";
echo 'Body: '.$resAppend->body()."\n";

if ($resAppend->json()['code'] === 0) {
    echo "\nSUCCESS: Write permission confirmed!\n";
} else {
    echo "\nFAILED: Write permission denied (Code: ".$resAppend->json()['code'].").\n";
    echo "IMPORTANT: Please check that the App is a 'Collaborator' with 'Can Edit' permissions on the Spreadsheet.\n";
}
