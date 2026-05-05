<?php

use App\Models\ShippingCompany;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$company = ShippingCompany::where('name', 'testestestestest')->first(['id', 'name', 'lark_base_token', 'lark_table_id']);

if ($company) {
    echo 'Found Company: '.$company->name."\n";
    echo 'Lark Base Token (DB): '.$company->lark_base_token."\n";
    echo 'Lark Table ID (DB): '.($company->lark_table_id ?? 'NULL')."\n";
} else {
    echo "Company not found.\n";
}
