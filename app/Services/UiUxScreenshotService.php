<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;
use RuntimeException;

class UiUxScreenshotService
{
    protected string $baseDir;

    public function __construct()
    {
        $this->baseDir = storage_path('app/uiux');
    }

    public function capture(string $url, string $viewportKey = 'desktop'): array
    {
        if (! in_array($viewportKey, ['desktop', 'tablet', 'mobile'], true)) {
            $viewportKey = 'desktop';
        }

        if (! is_dir($this->baseDir)) {
            mkdir($this->baseDir, 0755, true);
        }

        $runId = now()->format('Ymd_His').'_'.substr(md5(uniqid('', true)), 0, 8);
        $runDir = $this->baseDir.DIRECTORY_SEPARATOR.$runId;
        if (! is_dir($runDir)) {
            mkdir($runDir, 0755, true);
        }

        $script = base_path('scripts/uiux-screenshot.mjs');

        $result = Process::timeout(90)
            ->path(base_path())
            ->run('node '.escapeshellarg($script).' '.escapeshellarg($url).' '.escapeshellarg($viewportKey).' '.escapeshellarg($runDir));

        if ($result->failed()) {
            $output = trim($result->output()).' '.trim($result->errorOutput());

            throw new RuntimeException('Échec de la capture d\'écran: '.mb_substr($output, 0, 500));
        }

        $meta = json_decode(trim($result->output()), true);
        if (! is_array($meta) || empty($meta['screenshot'])) {
            throw new RuntimeException('Capture invalide: sortie inattendue du script.');
        }

        $meta['run_id'] = $runId;
        $meta['run_dir'] = $runDir;

        return $meta;
    }
}
