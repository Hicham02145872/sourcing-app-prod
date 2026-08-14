<?php

namespace Tests\Unit;

use App\Services\UiUxScreenshotService;
use Illuminate\Support\Facades\Process;
use Tests\TestCase;

class UiUxScreenshotServiceTest extends TestCase
{
    public function test_capture_fails_gracefully_when_script_errors(): void
    {
        Process::fake([
            '*' => Process::result(output: 'fatal: chromium crashed', exitCode: 1),
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('fatal: chromium crashed');

        app(UiUxScreenshotService::class)->capture('https://example.com');
    }

    public function test_capture_parses_metadata_and_creates_run_dir(): void
    {
        Process::fake([
            '*' => Process::result(output: json_encode([
                'url' => 'https://example.com',
                'viewport' => ['width' => 1440, 'height' => 900],
                'status' => 200,
                'screenshot' => 'screenshot-desktop.png',
                'page' => ['title' => 'Example', 'fullHeight' => 1200],
            ])),
        ]);

        $result = app(UiUxScreenshotService::class)->capture('https://example.com');

        $this->assertSame('screenshot-desktop.png', $result['screenshot']);
        $this->assertSame(200, $result['status']);
        $this->assertArrayHasKey('run_id', $result);
        $this->assertArrayHasKey('run_dir', $result);
        $this->assertDirectoryExists($result['run_dir']);
    }

    public function test_capture_rejects_unknown_viewport(): void
    {
        Process::fake([
            '*' => Process::result(output: json_encode(['screenshot' => 'x.png'])),
        ]);

        $result = app(UiUxScreenshotService::class)->capture('https://example.com', 'imax');

        $this->assertSame('x.png', $result['screenshot']);
    }
}
