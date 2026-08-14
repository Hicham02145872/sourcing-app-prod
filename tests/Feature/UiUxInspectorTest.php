<?php

namespace Tests\Feature;

use App\Livewire\Admin\DevDashboard;
use App\Models\Setting;
use App\Models\User;
use App\Services\UiUxInspectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UiUxInspectorTest extends TestCase
{
    use RefreshDatabase;

    public function test_developer_can_access_inspector(): void
    {
        $user = User::factory()->create(['role' => 'developer']);

        $response = $this->actingAs($user)->get(route('admin.dev.uiux-inspector'));

        $response->assertOk();
        $response->assertSee('UI/UX Inspector');
        $response->assertSee('Gemini');
    }

    public function test_admin_cannot_access_inspector(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(route('admin.dev.uiux-inspector'));

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.dev.uiux-inspector'));

        $response->assertRedirect(route('login'));
    }

    public function test_screenshot_route_returns_stored_file(): void
    {
        $user = User::factory()->create(['role' => 'developer']);
        $run = '20260101_000000_abcdef12';
        $dir = storage_path('app/uiux/'.$run);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($dir.'/screenshot-desktop.png', 'fake-png-bytes');

        $response = $this->actingAs($user)->get(route('admin.dev.uiux-screenshot', [
            'run' => $run,
            'file' => 'screenshot-desktop.png',
        ]));

        $response->assertOk();
        $this->assertSame('fake-png-bytes', $response->streamedContent());
    }

    public function test_screenshot_route_returns_404_when_missing(): void
    {
        $user = User::factory()->create(['role' => 'developer']);

        $response = $this->actingAs($user)->get(route('admin.dev.uiux-screenshot', [
            'run' => 'inexistant',
            'file' => 'x.png',
        ]));

        $response->assertNotFound();
    }

    public function test_effective_api_key_falls_back_to_setting(): void
    {
        config()->set('services.gemini.api_key', '');
        Setting::set('gemini_api_key', 'setting-key');

        $this->assertSame('setting-key', UiUxInspectionService::effectiveApiKey());
    }

    public function test_effective_api_key_prefers_env_config(): void
    {
        config()->set('services.gemini.api_key', 'env-key');
        Setting::set('gemini_api_key', 'setting-key');

        $this->assertSame('env-key', UiUxInspectionService::effectiveApiKey());
    }

    public function test_developer_can_save_gemini_api_key_from_dev_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'developer']);
        $this->actingAs($user);

        Livewire::test(DevDashboard::class)
            ->set('geminiApiKey', 'AIzaSy-test-key-1234567890abcdef')
            ->call('saveGeminiApiKey')
            ->assertHasNoErrors();

        $this->assertSame('AIzaSy-test-key-1234567890abcdef', Setting::get('gemini_api_key'));
    }

    public function test_developer_can_clear_gemini_api_key_from_dev_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'developer']);
        Setting::set('gemini_api_key', 'AIzaSy-old-key-9876543210abcdef');
        $this->actingAs($user);

        Livewire::test(DevDashboard::class)
            ->set('geminiApiKey', '')
            ->call('clearGeminiApiKey');

        $this->assertDatabaseMissing('settings', ['key' => 'gemini_api_key']);
    }
}
