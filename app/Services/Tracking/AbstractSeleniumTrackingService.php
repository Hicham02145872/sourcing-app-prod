<?php

namespace App\Services\Tracking;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

abstract class AbstractSeleniumTrackingService implements TrackingServiceInterface
{
    /**
     * The path to the Python script.
     */
    protected string $scriptPath;

    /**
     * The python executable path.
     */
    protected string $pythonPath;

    public function __construct()
    {
        $this->pythonPath = config('tracking.python_path', 'python');
        
        // Ensure the python home directory exists for selenium cache
        $pythonHome = storage_path('app/python_home');
        if (!file_exists($pythonHome)) {
            mkdir($pythonHome, 0775, true);
        }
    }

    /**
     * Get the environment variables for running the Python script.
     */
    protected function getEnvironment(): array
    {
        $env = [
            'SystemRoot' => env('SystemRoot', 'C:\\Windows'),
            'PATH' => env('PATH'),
            'TEMP' => env('TEMP'),
            'TMP' => env('TMP'),
            'PYTHONIOENCODING' => 'utf-8',
            'CHROME_BINARY_PATH' => config('tracking.chrome_binary_path'),
            'CHROMEDRIVER_PATH' => config('tracking.chromedriver_path'),
            'WDM_LOCAL' => '1',
            'WDM_LOG_LEVEL' => '0',
            'HOME' => storage_path('app/python_home'),
        ];

        if (config('tracking.proxy.enabled')) {
            $httpProxy = config('tracking.proxy.http');
            $httpsProxy = config('tracking.proxy.https');
            
            if ($httpProxy) {
                $env['HTTP_PROXY'] = $httpProxy;
                $env['http_proxy'] = $httpProxy;
            }
            
            if ($httpsProxy) {
                $env['HTTPS_PROXY'] = $httpsProxy;
                $env['https_proxy'] = $httpsProxy;
            }
            
            if ($noProxy = config('tracking.proxy.no_proxy')) {
                $env['NO_PROXY'] = $noProxy;
                $env['no_proxy'] = $noProxy;
            }
        }

        return array_merge($_SERVER, $env);
    }

    /**
     * Run the Python scraper and return the parsed result.
     * Uses the persistent HTTP server when enabled, falls back to process mode.
     */
    protected function runScript(string $trackingNumber): array
    {
        if (config('tracking.selenium_server.enabled')) {
            return $this->runViaHttpServer($trackingNumber);
        }

        return $this->runViaProcess($trackingNumber);
    }

    /**
     * Call the local Flask tracking server (P1.1 — persistent Chrome).
     * Falls back to process mode on connection failure so deployments are safe.
     */
    protected function runViaHttpServer(string $trackingNumber): array
    {
        $provider = $this->getServerProviderKey();
        $baseUrl  = rtrim(config('tracking.selenium_server.base_url', 'http://127.0.0.1:5001'), '/');
        $timeout  = (int) config('tracking.selenium_server.timeout', 30);
        $url      = "{$baseUrl}/track/{$provider}";

        try {
            Log::info("Starting {$this->getProviderName()} tracking via HTTP server for: {$trackingNumber}");

            $response = Http::timeout($timeout)
                ->post($url, ['tracking_number' => $trackingNumber]);

            if ($response->failed()) {
                Log::error("{$this->getProviderName()} HTTP Server returned {$response->status()}: ".$response->body());

                return [
                    'success' => false,
                    'error'   => "Selenium server HTTP {$response->status()}: ".$response->body(),
                ];
            }

            $decoded = $response->json();

            if (! is_array($decoded)) {
                Log::error("{$this->getProviderName()} Invalid JSON from server");

                return ['success' => false, 'error' => 'Invalid JSON from tracking server'];
            }

            return $this->parseScriptOutput($decoded);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::warning(
                "{$this->getProviderName()} Selenium server unreachable, falling back to process mode: "
                .$e->getMessage()
            );

            return $this->runViaProcess($trackingNumber);

        } catch (\Exception $e) {
            Log::error("{$this->getProviderName()} HTTP Server Exception: ".$e->getMessage());

            return ['success' => false, 'error' => 'Server exception: '.$e->getMessage()];
        }
    }

    /**
     * Legacy mode: spawn a Python process per request.
     */
    protected function runViaProcess(string $trackingNumber): array
    {
        try {
            Log::info("Starting {$this->getProviderName()} tracking (process mode) for: {$trackingNumber}");

            $result = Process::env($this->getEnvironment())
                ->timeout(120)
                ->run("{$this->pythonPath} \"{$this->scriptPath}\" \"{$trackingNumber}\"");

            if ($result->failed()) {
                Log::error("{$this->getProviderName()} Script Error: ".$result->errorOutput());

                return [
                    'success'    => false,
                    'error'      => 'Error executing script: '.$result->errorOutput(),
                    'raw_output' => $result->output(),
                ];
            }

            $output = trim($result->output());
            Log::debug("{$this->getProviderName()} Script Output: ".$output);

            $decoded = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("{$this->getProviderName()} JSON Parse Error: ".json_last_error_msg());

                return [
                    'success'    => false,
                    'error'      => 'JSON Parse error: '.json_last_error_msg(),
                    'raw_output' => $output,
                ];
            }

            return $this->parseScriptOutput($decoded);

        } catch (\Exception $e) {
            Log::error("{$this->getProviderName()} Service Exception: ".$e->getMessage());

            return [
                'success' => false,
                'error'   => 'Service exception: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Provider key used in the HTTP server route: /track/{key}.
     * Default: lowercase provider name with spaces removed (e.g. "ChoiceXP" → "choicexp").
     */
    protected function getServerProviderKey(): string
    {
        return strtolower(str_replace([' ', '-', '_'], '', $this->getProviderName()));
    }

    /**
     * Parse the script output (provider-specific).
     *
     * @param  mixed  $decoded
     */
    abstract protected function parseScriptOutput($decoded): array;

    /**
     * Get the provider name for logging.
     */
    abstract protected function getProviderName(): string;
}
