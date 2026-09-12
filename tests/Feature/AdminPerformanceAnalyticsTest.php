<?php

namespace Tests\Feature;

use App\Models\SourcingOrder;
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

    public function test_workload_counts_orders_per_admin_by_phase(): void
    {
        $admin1 = User::factory()->create(['role' => 'admin']);
        $admin2 = User::factory()->create(['role' => 'admin']);

        // admin1: 2 paid, 1 delivered → total 3
        SourcingOrder::factory()->create(['assigned_to_admin_id' => $admin1->id, 'status' => 'paid']);
        SourcingOrder::factory()->create(['assigned_to_admin_id' => $admin1->id, 'status' => 'paid']);
        SourcingOrder::factory()->create(['assigned_to_admin_id' => $admin1->id, 'status' => 'delivered']);

        // admin2: 3 in_transit_china, 1 delivery_failed → total 4
        SourcingOrder::factory()->create(['assigned_to_admin_id' => $admin2->id, 'status' => 'in_transit_china']);
        SourcingOrder::factory()->create(['assigned_to_admin_id' => $admin2->id, 'status' => 'in_transit_china']);
        SourcingOrder::factory()->create(['assigned_to_admin_id' => $admin2->id, 'status' => 'in_transit_china']);
        SourcingOrder::factory()->create(['assigned_to_admin_id' => $admin2->id, 'status' => 'delivery_failed']);

        $component = Livewire::actingAs($this->superAdmin)
            ->test(\App\Livewire\Admin\AdminPerformanceAnalytics::class);

        $metrics = $component->get('metrics');
        $this->assertCount(2, $metrics);

        // Sorted by total descending
        $this->assertEquals($admin2->id, $metrics[0]['admin_id']);

        $admin2Metric = collect($metrics)->firstWhere('admin_id', $admin2->id);
        $this->assertEquals(4, $admin2Metric['total']);
        $this->assertEquals(3, $admin2Metric['transit']);
        $this->assertEquals(1, $admin2Metric['issues']);

        $admin1Metric = collect($metrics)->firstWhere('admin_id', $admin1->id);
        $this->assertEquals(3, $admin1Metric['total']);
        $this->assertEquals(2, $admin1Metric['paid']);
        $this->assertEquals(1, $admin1Metric['delivered']);
    }

    public function test_unassigned_orders_listed_as_unassigned_row(): void
    {
        SourcingOrder::factory()->create(['assigned_to_admin_id' => null, 'status' => 'pending_payment']);
        SourcingOrder::factory()->create(['assigned_to_admin_id' => null, 'status' => 'pending_payment']);

        $component = Livewire::actingAs($this->superAdmin)
            ->test(\App\Livewire\Admin\AdminPerformanceAnalytics::class);

        $metrics = $component->get('metrics');
        $unassigned = collect($metrics)->firstWhere('admin_id', 0);

        $this->assertNotNull($unassigned);
        $this->assertEquals(__('Unassigned'), $unassigned['admin_name']);
        $this->assertEquals(2, $unassigned['total']);
        $this->assertEquals(2, $unassigned['pending_payment']);
    }

    public function test_no_orders_returns_empty_metrics(): void
    {
        $component = Livewire::actingAs($this->superAdmin)
            ->test(\App\Livewire\Admin\AdminPerformanceAnalytics::class);

        $metrics = $component->get('metrics');
        $this->assertIsArray($metrics);
        $this->assertEmpty($metrics);
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
