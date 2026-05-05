<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ShippingCompany;
use App\Services\ShippingCompanySheetService;

$companies = ShippingCompany::where('is_active', true)->get();

foreach ($companies as $company) {
    echo "Testing {$company->name} (ID: {$company->id})...\n";
    echo 'Sheet ID: '.($company->google_sheet_id ?: 'MISSING')."\n";
    echo 'Sheet Name: '.($company->sheet_name ?: 'DEFAULT (Sheet1)')."\n";

    if (! $company->google_sheet_id) {
        echo "SKIP: No Google Sheet ID.\n\n";

        continue;
    }

    try {
        $service = new ShippingCompanySheetService($company);
        $result = $service->ensureHeaders();
        if ($result['success']) {
            echo 'SUCCESS: '.$result['message']."\n";
        } else {
            echo 'FAILED SERVICE RESULT: '.$result['message']."\n";
        }
    } catch (\Exception $e) {
        echo 'EXCEPTION: '.$e->getMessage()."\n";
        if (strpos($e->getMessage(), '404') !== false) {
            echo "DETAIL: This Spreadsheet ID or the specific Tab Name likely does not exist or service account lacks access.\n";
        }
    }
    echo "---------------------------\n\n";
}
