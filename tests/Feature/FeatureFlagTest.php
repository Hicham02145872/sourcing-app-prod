<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeatureFlagTest extends TestCase
{
    use RefreshDatabase;

    public function test_feature_flag_service_defaults_to_visible(): void
    {
        $service = app(\App\Services\FeatureFlagService::class);
        $this->assertEquals('visible', $service->getFlagStatus('non_existent_feature'));
        $this->assertTrue($service->isEnabled('non_existent_feature'));
        $this->assertTrue($service->isVisible('non_existent_feature'));
        $this->assertFalse($service->isComingSoon('non_existent_feature'));
    }

    public function test_feature_flag_status_can_be_changed(): void
    {
        \App\Models\FeatureFlag::create([
            'key' => 'test_feature',
            'name' => 'Test Feature',
            'status' => 'hidden',
        ]);

        $service = app(\App\Services\FeatureFlagService::class);
        // Clear cache
        \Illuminate\Support\Facades\Cache::forget('feature_flag_test_feature');

        $this->assertEquals('hidden', $service->getFlagStatus('test_feature'));
        $this->assertFalse($service->isEnabled('test_feature'));
    }

    public function test_feature_flag_can_be_role_restricted(): void
    {
        $user = \App\Models\User::factory()->create(['role' => 'client']);
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);

        \App\Models\FeatureFlag::create([
            'key' => 'admin_only_feature',
            'name' => 'Admin Feature',
            'status' => 'visible',
            'roles' => ['admin'],
        ]);

        $service = app(\App\Services\FeatureFlagService::class);
        \Illuminate\Support\Facades\Cache::forget('feature_flag_admin_only_feature');

        $this->assertFalse($service->isEnabled('admin_only_feature', $user));
        $this->assertTrue($service->isEnabled('admin_only_feature', $admin));
    }

    public function test_feature_middleware_aborts_when_hidden(): void
    {
        $user = \App\Models\User::factory()->create();

        \App\Models\FeatureFlag::create([
            'key' => 'hidden_feature',
            'name' => 'Hidden',
            'status' => 'hidden',
        ]);

        \Illuminate\Support\Facades\Route::get('/test-feature', function () {
            return 'success';
        })->middleware([\App\Http\Middleware\CheckFeatureMiddleware::class.':hidden_feature']);

        $response = $this->actingAs($user)->get('/test-feature');
        $response->assertStatus(404);
    }

    public function test_feature_middleware_allows_when_visible(): void
    {
        $user = \App\Models\User::factory()->create();

        \App\Models\FeatureFlag::create([
            'key' => 'visible_feature',
            'name' => 'Visible',
            'status' => 'visible',
        ]);

        \Illuminate\Support\Facades\Route::get('/test-visible', function () {
            return 'success';
        })->middleware([\App\Http\Middleware\CheckFeatureMiddleware::class.':visible_feature']);

        $response = $this->actingAs($user)->get('/test-visible');
        $response->assertStatus(200);
        $response->assertSee('success');
    }
}
