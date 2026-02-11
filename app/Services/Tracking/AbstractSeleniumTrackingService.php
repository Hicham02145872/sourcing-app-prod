<?php

namespace App\Services\Tracking;

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
     * Run the Python script and return the parsed result.
     */
    protected function runScript(string $trackingNumber): array
    {
        try {
            Log::info("Starting {$this->getProviderName()} tracking for: {$trackingNumber}");

            $result = Process::env($this->getEnvironment())
                ->timeout(120)
                ->run("{$this->pythonPath} \"{$this->scriptPath}\" \"{$trackingNumber}\"");

            if ($result->failed()) {
                Log::error("{$this->getProviderName()} Script Error: ".$result->errorOutput());

                return [
                    'success' => false,
                    'error' => 'Error executing script: '.$result->errorOutput(),
                    'raw_output' => $result->output(),
                ];
            }

            $output = trim($result->output());
            Log::debug("{$this->getProviderName()} Script Output: ".$output);

            $decoded = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("{$this->getProviderName()} JSON Parse Error: ".json_last_error_msg());

                return [
                    'success' => false,
                    'error' => 'JSON Parse error: '.json_last_error_msg(),
                    'raw_output' => $output,
                ];
            }

            return $this->parseScriptOutput($decoded);

        } catch (\Exception $e) {
            Log::error("{$this->getProviderName()} Service Exception: ".$e->getMessage());

            return [
                'success' => false,
                'error' => 'Service exception: '.$e->getMessage(),
            ];
        }
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
