<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestTrackingRegression extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tracking:test-regression {--provider=all : The provider to test (itdida, faster, choicexp, ups, all)}';

    // ...

    public function handle(
        \App\Services\Tracking\ItdidaTrackingService $itdidaService,
        \App\Services\Tracking\FasterTrackingService $fasterService,
        \App\Services\Tracking\ChoiceXPTrackingService $choiceXPService,
        \App\Services\Tracking\UPSTrackingService $upsService
    )
    {
        $provider = $this->option('provider');
        $this->info("Starting tracking regression test for: {$provider}");

        $testCases = [
            'itdida' => $this->getRecentSuccessfulNumbers('itdida'),
            'faster' => $this->getRecentSuccessfulNumbers('faster'),
            'choicexp' => $this->getRecentSuccessfulNumbers('choicexp'),
            'ups' => $this->getRecentSuccessfulNumbers('ups'),
        ];

        if ($provider !== 'all') {
            $testCases = array_intersect_key($testCases, [$provider => []]);
        }

        foreach ($testCases as $serviceName => $numbers) {
            if (empty($numbers)) {
                $this->warn("No recent successful tracking numbers found for {$serviceName}. Skipping.");
                continue;
            }

            $this->info("Testing {$serviceName} with " . count($numbers) . " numbers...");
            $service = match($serviceName) {
                'itdida' => $itdidaService,
                'faster' => $fasterService,
                'choicexp' => $choiceXPService,
                'ups' => $upsService,
            };

            foreach ($numbers as $number) {
                $this->testProvider($service, $serviceName, $number);
            }
        }
        
        $this->info("Regression test completed.");
    }

    protected function getRecentSuccessfulNumbers(string $provider, int $limit = 2): array
    {
        // Fetch from TrackingLog where status was success
        return \App\Models\TrackingLog::where('provider', 'LIKE', "%{$provider}%")
            ->where('status', 'NOT LIKE', '%Failed%')
            ->where('status', 'NOT LIKE', '%Error%')
            ->latest()
            ->take($limit)
            ->pluck('tracking_number')
            ->toArray();
    }

    protected function testProvider($service, string $name, string $number)
    {
        $start = microtime(true);
        try {
            $result = $service->getTrackingInfo($number);
            $duration = round((microtime(true) - $start) * 1000, 2);

            if ($result['success']) {
                $this->info("✅ [{$name}] {$number} - Success ({$duration}ms)");
            } else {
                $this->error("❌ [{$name}] {$number} - Failed: " . ($result['error'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            $this->error("❌ [{$name}] {$number} - Exception: " . $e->getMessage());
        }
    }
}
