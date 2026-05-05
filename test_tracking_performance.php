<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\Tracking\UnifiedTrackingService;
use Illuminate\Support\Facades\Cache;

$trackingNumber = 'DBC25406382';

echo "=== ChoiceXP Tracking Performance Test ===\n\n";

// Clear cache for this tracking number
Cache::forget("tracking:{$trackingNumber}");
echo "Cache cleared for: {$trackingNumber}\n\n";

// First call (uncached)
echo "📊 First call (UNCACHED - will scrape with Selenium):\n";
$start = microtime(true);
$result1 = app(UnifiedTrackingService::class)->track($trackingNumber);
$time1 = round((microtime(true) - $start) * 1000, 2);

echo "   ⏱️  Time: {$time1}ms\n";
echo '   ✅ Success: '.($result1['success'] ? 'Yes' : 'No')."\n";
echo '   🚚 Provider: '.($result1['provider'] ?? 'N/A')."\n";
echo '   📦 Events: '.count($result1['events'] ?? [])."\n";
if (! $result1['success']) {
    echo '   ❌ Error: '.($result1['error'] ?? 'Unknown')."\n";
}
echo "\n";

// Wait a moment
sleep(1);

// Second call (cached)
echo "📊 Second call (CACHED - instant from cache):\n";
$start = microtime(true);
$result2 = app(UnifiedTrackingService::class)->track($trackingNumber);
$time2 = round((microtime(true) - $start) * 1000, 2);

echo "   ⏱️  Time: {$time2}ms\n";
echo '   ✅ Success: '.($result2['success'] ? 'Yes' : 'No')."\n";
echo '   🚚 Provider: '.($result2['provider'] ?? 'N/A')."\n";
echo '   📦 Events: '.count($result2['events'] ?? [])."\n\n";

// Performance comparison
$improvement = $time1 > 0 ? round((($time1 - $time2) / $time1) * 100, 2) : 0;
echo "=== Performance Improvement ===\n";
echo "   🚀 Speed improvement: {$improvement}%\n";
echo '   ⚡ Cached response is '.round($time1 / max($time2, 1), 2)."x faster\n";
