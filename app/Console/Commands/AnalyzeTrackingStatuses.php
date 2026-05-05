<?php

namespace App\Console\Commands;

use App\Services\Tracking\UnifiedTrackingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AnalyzeTrackingStatuses extends Command
{
    protected $signature = 'tracking:analyze-statuses 
                            {--tracking-number= : Specific tracking number to analyze}
                            {--provider= : Specific provider to test (faster, itdida, choicexp, ups)}
                            {--save-results : Save results to file}';

    protected $description = 'Analyze tracking statuses returned by different providers to build status mapping';

    protected UnifiedTrackingService $trackingService;

    public function __construct(UnifiedTrackingService $trackingService)
    {
        parent::__construct();
        $this->trackingService = $trackingService;
    }

    public function handle()
    {
        $this->info('🔍 Analyzing tracking statuses from providers...');
        $this->newLine();

        // Test tracking numbers (examples - should be replaced with real ones)
        $testNumbers = [
            'faster' => $this->option('tracking-number') ?: 'ME49508327', // Example Faster number
            'itdida' => $this->option('tracking-number') ?: 'ME49508327', // Example ITDIDA number
            'choicexp' => $this->option('tracking-number') ?: 'DBC123456', // Example ChoiceXP number
            'ups' => $this->option('tracking-number') ?: '1Z14V4W16890506495', // Example UPS number
        ];

        $providers = $this->option('provider') 
            ? [strtolower($this->option('provider'))] 
            : ['faster', 'itdida', 'choicexp', 'ups'];

        $results = [];

        foreach ($providers as $provider) {
            if (!isset($testNumbers[$provider])) {
                continue;
            }

            $trackingNumber = $testNumbers[$provider];
            $this->info("📡 Testing {$provider} with tracking number: {$trackingNumber}");

            try {
                $result = $this->trackingService->track($trackingNumber, $provider);

                if ($result['success'] ?? false) {
                    $this->info("✅ Success - Found " . count($result['events'] ?? []) . " events");
                    
                    $analysis = $this->analyzeProviderStatuses($provider, $result);
                    $results[$provider] = $analysis;

                    // Display summary
                    $this->displayAnalysis($provider, $analysis);
                } else {
                    $this->warn("❌ Failed: " . ($result['error'] ?? 'Unknown error'));
                    $results[$provider] = [
                        'success' => false,
                        'error' => $result['error'] ?? 'Unknown error',
                    ];
                }
            } catch (\Exception $e) {
                $this->error("❌ Exception: " . $e->getMessage());
                $results[$provider] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }

            $this->newLine();
        }

        // Save results if requested
        if ($this->option('save-results')) {
            $filename = 'tracking_status_analysis_' . date('Y-m-d_H-i-s') . '.json';
            $filepath = storage_path('app/' . $filename);
            file_put_contents($filepath, json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $this->info("💾 Results saved to: {$filepath}");
        }

        $this->info('✅ Analysis complete!');
        
        return 0;
    }

    protected function analyzeProviderStatuses(string $provider, array $result): array
    {
        $analysis = [
            'provider' => $provider,
            'tracking_number' => $result['tracking_number'] ?? '',
            'events_count' => count($result['events'] ?? []),
            'current_status' => $result['current_status'] ?? $result['current_status_fr'] ?? 'Unknown',
            'unique_statuses' => [],
            'unique_locations' => [],
            'status_patterns' => [],
            'events' => [],
        ];

        $events = $result['events'] ?? [];
        
        foreach ($events as $event) {
            // Collect unique statuses (raw and translated)
            $statusRaw = $event['status'] ?? '';
            $statusEn = $event['status_en'] ?? '';
            $statusFr = $event['status_fr'] ?? '';
            $location = $event['location'] ?? $event['location_raw'] ?? '';
            
            if ($statusRaw && !in_array($statusRaw, $analysis['unique_statuses'])) {
                $analysis['unique_statuses'][] = $statusRaw;
            }
            
            if ($statusEn && !in_array($statusEn, $analysis['unique_statuses'])) {
                $analysis['unique_statuses'][] = $statusEn;
            }
            
            if ($statusFr && !in_array($statusFr, $analysis['unique_statuses'])) {
                $analysis['unique_statuses'][] = $statusFr;
            }
            
            if ($location && !in_array($location, $analysis['unique_locations'])) {
                $analysis['unique_locations'][] = $location;
            }

            // Store event details
            $analysis['events'][] = [
                'date' => $event['date'] ?? '',
                'status_raw' => $statusRaw,
                'status_en' => $statusEn,
                'status_fr' => $statusFr,
                'location' => $location,
            ];
        }

        return $analysis;
    }

    protected function displayAnalysis(string $provider, array $analysis): void
    {
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("Provider: " . strtoupper($provider));
        $this->line("Events found: " . $analysis['events_count']);
        $this->line("Current status: " . $analysis['current_status']);
        $this->newLine();

        if (!empty($analysis['unique_statuses'])) {
            $this->info("📋 Unique Statuses Found:");
            foreach ($analysis['unique_statuses'] as $status) {
                $this->line("  • {$status}");
            }
            $this->newLine();
        }

        if (!empty($analysis['unique_locations'])) {
            $this->info("📍 Unique Locations Found:");
            foreach ($analysis['unique_locations'] as $location) {
                $this->line("  • {$location}");
            }
            $this->newLine();
        }

        if (!empty($analysis['events'])) {
            $this->info("📦 Latest Events (first 5):");
            foreach (array_slice($analysis['events'], 0, 5) as $event) {
                $this->line("  Date: " . ($event['date'] ?? 'N/A'));
                $this->line("  Status (raw): " . ($event['status_raw'] ?? 'N/A'));
                $this->line("  Status (EN): " . ($event['status_en'] ?? 'N/A'));
                $this->line("  Status (FR): " . ($event['status_fr'] ?? 'N/A'));
                $this->line("  Location: " . ($event['location'] ?? 'N/A'));
                $this->line("  ────────────────────────────────");
            }
        }
    }
}
