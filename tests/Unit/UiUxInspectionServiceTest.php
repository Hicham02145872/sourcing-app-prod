<?php

namespace Tests\Unit;

use App\Services\UiUxInspectionService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UiUxInspectionServiceTest extends TestCase
{
    protected function fakePng(): string
    {
        $path = storage_path('app/uiux-test-fixture.png');
        file_put_contents($path, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
        ));

        return $path;
    }

    public function test_missing_api_key_throws(): void
    {
        config()->set('services.gemini.api_key', null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('GEMINI_API_KEY');

        app(UiUxInspectionService::class)->analyze($this->fakePng());
    }

    public function test_analyze_returns_structured_result(): void
    {
        config()->set('services.gemini.api_key', 'test-key');

        Http::fake([
            '*' => Http::response([
                'candidates' => [
                    ['content' => ['parts' => [['text' => json_encode([
                        'overall_score' => 72,
                        'grade' => 'B',
                        'summary' => 'Page bien structurée.',
                        'findings' => [
                            ['severity' => 'minor', 'title' => 'Contraste faible', 'recommendation' => 'Renforcer la couleur.'],
                        ],
                    ])]]]],
                ],
            ]),
        ]);

        $result = app(UiUxInspectionService::class)->analyze($this->fakePng());

        $this->assertSame(72, $result['overall_score']);
        $this->assertSame('B', $result['grade']);
        $this->assertSame('minor', $result['findings'][0]['severity']);
    }

    public function test_analyze_strips_json_code_fence(): void
    {
        config()->set('services.gemini.api_key', 'test-key');

        Http::fake([
            '*' => Http::response([
                'candidates' => [
                    ['content' => ['parts' => [['text' => "```json\n".json_encode(['overall_score' => 50, 'findings' => []])."\n```"]]]],
                ],
            ]),
        ]);

        $result = app(UiUxInspectionService::class)->analyze($this->fakePng());

        $this->assertSame(50, $result['overall_score']);
    }

    public function test_http_error_throws_with_api_message(): void
    {
        config()->set('services.gemini.api_key', 'test-key');

        Http::fake([
            '*' => Http::response(['error' => ['message' => 'API key not valid.']], 403),
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('API key not valid');

        app(UiUxInspectionService::class)->analyze($this->fakePng());
    }

    public function test_non_json_response_throws(): void
    {
        config()->set('services.gemini.api_key', 'test-key');

        Http::fake([
            '*' => Http::response([
                'candidates' => [
                    ['content' => ['parts' => [['text' => 'ceci n\'est pas du JSON']]]],
                ],
            ]),
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('JSON');

        app(UiUxInspectionService::class)->analyze($this->fakePng());
    }
}
