<?php

namespace Tests\Feature;

use App\Models\RequestStatusLog;
use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPerformanceAnalyticsTest extends TestCase
{
    private User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
    }

    public function test_status_transition_creates_log_with_actor(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $request = SourcingRequest::factory()->create(['status' => 'pending']);

        $this->actingAs($admin);
        $request->update(['status' => 'in_review']);

        $this->assertDatabaseHas('request_status_logs', [
            'sourcing_request_id' => $request->getKey(),
            'from_status' => 'pending',
            'to_status' => 'in_review',
            'changed_by_user_id' => $admin->getKey(),
        ]);
    }

    public function test_transition_without_actor_logs_null_user(): void
    {
        $request = SourcingRequest::factory()->create(['status' => 'pending']);

        $request->update(['status' => 'in_review']);

        $this->assertDatabaseHas('request_status_logs', [
            'sourcing_request_id' => $request->getKey(),
            'from_status' => 'pending',
            'to_status' => 'in_review',
            'changed_by_user_id' => null,
        ]);
    }

    public function test_analytics_aggregates_only_within_date_range(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $inRangeRequest = SourcingRequest::factory()->create(['status' => 'pending']);
        $outOfRangeRequest = SourcingRequest::factory()->create(['status' => 'pending']);

        RequestStatusLog::create([
            'sourcing_request_id' => $inRangeRequest->getKey(),
            'from_status' => 'pending',
            'to_status' => 'in_review',
            'changed_by_user_id' => $admin->getKey(),
            'changed_at' => now(),
        ]);

        RequestStatusLog::create([
            'sourcing_request_id' => $outOfRangeRequest->getKey(),
            'from_status' => 'pending',
            'to_status' => 'in_review',
            'changed_by_user_id' => $admin->getKey(),
            'changed_at' => now()->subMonth(),
        ]);

        $component = Livewire::actingAs($this->superAdmin)
            ->test(\App\Livewire\Admin\AdminPerformanceAnalytics::class)
            ->set('startDate', now()->startOfMonth()->format('Y-m-d'))
            ->set('endDate', now()->endOfMonth()->format('Y-m-d'))
            ->call('applyDateRange');

        $metrics = $component->get('metrics');
        $this->assertNotEmpty($metrics);
        $this->assertEquals(1, $metrics[0]['reviews']);
    }

    public function test_acceptance_rate_null_when_no_responses(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $request = SourcingRequest::factory()->create(['status' => 'pending']);

        RequestStatusLog::create([
            'sourcing_request_id' => $request->getKey(),
            'from_status' => 'pending',
            'to_status' => 'in_review',
            'changed_by_user_id' => $admin->getKey(),
            'changed_at' => now(),
        ]);

        $component = Livewire::actingAs($this->superAdmin)
            ->test(\App\Livewire\Admin\AdminPerformanceAnalytics::class);

        $metrics = $component->get('metrics');
        $this->assertNotNull($metrics);
        $adminMetric = collect($metrics)->firstWhere('admin_id', $admin->getKey());
        $this->assertNotNull($adminMetric);
        $this->assertNull($adminMetric['acceptance_rate']);
        $this->assertEquals(1, $adminMetric['reviews']);
        $this->assertEquals(0, $adminMetric['responses']);
    }

    public function test_reset_date_range_shows_full_dataset(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $request = SourcingRequest::factory()->create(['status' => 'pending']);

        RequestStatusLog::create([
            'sourcing_request_id' => $request->getKey(),
            'from_status' => 'pending',
            'to_status' => 'in_review',
            'changed_by_user_id' => $admin->getKey(),
            'changed_at' => now()->subMonth(),
        ]);

        Livewire::actingAs($this->superAdmin)
            ->test(\App\Livewire\Admin\AdminPerformanceAnalytics::class)
            ->set('startDate', now()->format('Y-m-d'))
            ->set('endDate', now()->format('Y-m-d'))
            ->call('applyDateRange')
            ->assertSet('metrics', [])
            ->call('resetDateRange')
            ->assertSet('startDate', '')
            ->assertSet('endDate', '');

        $component = Livewire::actingAs($this->superAdmin)
            ->test(\App\Livewire\Admin\AdminPerformanceAnalytics::class);

        $metrics = $component->get('metrics');
        $this->assertNotEmpty($metrics);
    }

    public function test_analytics_page_returns_404_when_feature_flag_disabled(): void
    {
        \App\Models\FeatureFlag::query()->updateOrCreate(
            ['key' => 'admin_performance_analytics'],
            ['name' => 'Admin Performance Analytics', 'status' => 'hidden']
        );
        Cache::forget('feature_flag_admin_performance_analytics');

        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.super-admin.analytics.admin-performance'));

        $response->assertStatus(404);
    }

    public function test_analytics_page_returns_200_when_feature_flag_enabled(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.super-admin.analytics.admin-performance'));

        $response->assertStatus(200);
    }
}