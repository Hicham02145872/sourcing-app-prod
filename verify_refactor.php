<?php

use App\Services\GoogleSheetService;
use Illuminate\Support\Facades\Cache;

try {
    $service = app(GoogleSheetService::class);

    // Use Reflection to access protected methods
    $reflection = new \ReflectionClass(GoogleSheetService::class);

    $methodGetId = $reflection->getMethod('getSheetIdByName');
    $methodGetId->setAccessible(true);

    $methodGetKey = $reflection->getMethod('getSheetCacheKey');
    $methodGetKey->setAccessible(true);

    $setting = App\Models\GoogleSheetSetting::first();
    $sheetName = $setting->sheet_name;
    echo "Testing with Sheet Name: $sheetName\n";
    $cacheKey = $methodGetKey->invoke($service, $sheetName);

    // Clear cache
    Cache::forget($cacheKey);

    $id1 = $methodGetId->invoke($service, $sheetName);
    $inCache = Cache::has($cacheKey);
    $id2 = $methodGetId->invoke($service, $sheetName);

    echo "\nVERIFICATION_START\n";
    echo 'ID1: '.(string) $id1."\n";
    echo 'InCache: '.($inCache ? 'YES' : 'NO')."\n";
    echo 'ID2: '.(string) $id2."\n";
    echo "VERIFICATION_END\n";

    if ($id1 === $id2 && $id1 !== null) {
        echo "FINAL SUCCESS: Structured refactoring verified.\n";
    }

} catch (\Exception $e) {
    echo 'ERROR: '.$e->getMessage()."\n";
}
