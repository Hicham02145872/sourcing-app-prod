<?php

namespace Tests\Feature;

use App\Models\FeatureFlag;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\User;
use App\Notifications\SlaDeadlineExceeded;
use App\Notifications\SourcingOrderDeadlineExceeded;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SlaDeadlineAutolockTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function enableSlaFlag(): void
    {
        FeatureFlag::create([
            'key' => 'sla_deadlines_autolock',
            'name' => 'SLA deadlines autolock',
            'status' => 'visible',
        ]);
    }

    private function disableSlaFlag(): void
    {
        FeatureFlag::create([
            'key' => 'sla_deadlines_autolock',
            'name' => 'SLA deadlines autolock',
            'status' => 'hidden',
        ]);
    }

    private function makeRequest(User $client, string $status, User $admin, array $overrides = []): SourcingRequest
    {
        return SourcingRequest::factory()->create(array_merge([
            'user_id' => $client->id,
            'status' => $status,
            'assigned_to_admin_id' => $admin->id,
            'assigned_at' => now()->subDays(2),
        ], $overrides));
    }

    private function makeOrder(User $client, string $status, User $admin, array $overrides = []): SourcingOrder
    {
        return SourcingOrder::factory()->create(array_merge([
            'user_id' => $client->id,
            'status' => $status,
            'assigned_to_admin_id' => $admin->id,
        ], $overrides));
    }

    /** @test */
    public function a_status_transition_updates_status_changed_at(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $request = $this->makeRequest($client, 'pending', $admin);

        $this->assertNull($request->status_changed_at);
        $this->assertNotTrue($request->is_restricted_due_to_delay);

        $request->status = 'in_review';
        $request->save();

        $this->assertNotNull($request->fresh()->status_changed_at);
        $this->assertFalse($request->fresh()->is_restricted_due_to_delay);
    }

    /** @test */
    public function the_check_command_flags_an_overdue_request_and_notifies_the_assigned_admin(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $request = $this->makeRequest($client, 'in_review', $admin, [
            'status_changed_at' => now()->subHours(25),
        ]);

        Notification::fake();

        $this->artisan('workflow:check-deadlines')
            ->assertSuccessful();

        $this->assertTrue($request->fresh()->is_restricted_due_to_delay);
        Notification::assertSentTo($admin, SlaDeadlineExceeded::class);
    }

    /** @test */
    public function non_sla_statuses_are_not_restricted(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $request = $this->makeRequest($client, 'pending', $admin, [
            'status_changed_at' => now()->subHours(25),
        ]);

        $this->artisan('workflow:check-deadlines')
            ->assertSuccessful();

        $this->assertFalse($request->fresh()->is_restricted_due_to_delay);
    }

    /** @test */
    public function a_transition_after_restriction_clears_it_and_refreshes_the_timestamp(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $request = $this->makeRequest($client, 'pending', $admin);

        $request->status = 'quoted';
        $request->save();
        $request->update(['status_changed_at' => now()->subHours(50)]);
        $request->update(['is_restricted_due_to_delay' => true]);
        $request->refresh();

        $this->assertTrue($request->is_restricted_due_to_delay);
        $this->assertTrue($request->status_changed_at->lt(now()->subHours(24)));

        $request->status = 'negotiating';
        $request->save();
        $request->refresh();

        $this->assertFalse($request->is_restricted_due_to_delay);
        $this->assertTrue($request->status_changed_at->gt(now()->subMinutes(2)));
    }

    /** @test */
    public function the_command_is_a_no_op_when_the_flag_is_disabled(): void
    {
        $this->disableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $request = $this->makeRequest($client, 'in_review', $admin, [
            'status_changed_at' => now()->subHours(25),
        ]);

        Notification::fake();

        $this->artisan('workflow:check-deadlines')
            ->assertSuccessful();

        $this->assertFalse($request->fresh()->is_restricted_due_to_delay);
        Notification::assertNothingSent();
    }

    /** @test */
    public function the_migration_backfills_status_changed_at_from_existing_status_timestamps(): void
    {
        $this->assertTrue(Schema::hasColumn('sourcing_requests', 'status_changed_at'));
        $this->assertTrue(Schema::hasColumn('sourcing_requests', 'is_restricted_due_to_delay'));

        $client = User::factory()->create(['role' => 'client']);
        $timestamp = now()->subDays(3)->format('Y-m-d H:i:s');
        $request = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'negotiating',
            'status_timestamps' => ['negotiating' => $timestamp],
            'status_changed_at' => null,
        ]);

        // Mirrors the backfill loop of the migration, against rows that predate it.
        DB::table('sourcing_requests')
            ->select('id', 'status', 'status_timestamps', 'updated_at')
            ->orderBy('id')
            ->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    $timestamps = json_decode((string) ($row->status_timestamps ?? 'null'), true);
                    $backfilled = is_array($timestamps) && isset($timestamps[$row->status])
                        ? $timestamps[$row->status]
                        : $row->updated_at;

                    DB::table('sourcing_requests')
                        ->where('id', $row->id)
                        ->update(['status_changed_at' => $backfilled]);
                }
            });

        $this->assertSame(
            $timestamp,
            $request->fresh()->status_changed_at->format('Y-m-d H:i:s')
        );
    }

    /** @test */
    public function dashboard_shows_the_sla_banner_when_requests_are_restricted(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $this->makeRequest($client, 'in_review', $admin, [
            'is_restricted_due_to_delay' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Action needed on your folders');
    }

    /** @test */
    public function dashboard_hides_the_sla_banner_when_nothing_is_restricted(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $this->makeRequest($client, 'in_review', $admin);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('Action needed on your folders');
    }

    /** @test */
    public function a_status_transition_updates_order_status_changed_at_and_clears_restriction(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->makeOrder($client, 'pending_payment', $admin);

        $this->assertNull($order->status_changed_at);
        $this->assertNotTrue($order->is_restricted_due_to_delay);

        $order->status = 'paid';
        $order->save();

        $this->assertNotNull($order->fresh()->status_changed_at);
        $this->assertFalse($order->fresh()->is_restricted_due_to_delay);
    }

    /** @test */
    public function the_check_command_flags_an_overdue_order_and_notifies_the_assigned_admin(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->makeOrder($client, 'paid', $admin, [
            'status_changed_at' => now()->subHours(25),
        ]);

        Notification::fake();

        $this->artisan('workflow:check-deadlines')
            ->assertSuccessful();

        $this->assertTrue($order->fresh()->is_restricted_due_to_delay);
        Notification::assertSentTo($admin, SourcingOrderDeadlineExceeded::class);
    }

    /** @test */
    public function an_overdue_paid_order_also_notifies_super_admins(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->makeOrder($client, 'paid', $admin, [
            'status_changed_at' => now()->subHours(25),
        ]);

        Notification::fake();

        $this->artisan('workflow:check-deadlines')
            ->assertSuccessful();

        Notification::assertSentTo($admin, SourcingOrderDeadlineExceeded::class);
        Notification::assertSentTo($superAdmin, SourcingOrderDeadlineExceeded::class);
    }

    /** @test */
    public function non_sla_order_statuses_are_not_restricted(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $order = $this->makeOrder($client, 'shipment_preparing', $admin, [
            'status_changed_at' => now()->subHours(49),
        ]);

        $this->artisan('workflow:check-deadlines')
            ->assertSuccessful();

        $this->assertFalse($order->fresh()->is_restricted_due_to_delay);
    }

    /** @test */
    public function the_orders_migration_has_the_deadline_columns_and_backfills_status_changed_at(): void
    {
        $this->assertTrue(Schema::hasColumn('sourcing_orders', 'status_changed_at'));
        $this->assertTrue(Schema::hasColumn('sourcing_orders', 'is_restricted_due_to_delay'));

        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $timestamp = now()->subDays(3)->format('Y-m-d H:i:s');
        $order = $this->makeOrder($client, 'paid', $admin, [
            'status' => 'in_transit_china',
            'status_timestamps' => ['in_transit_china' => $timestamp],
            'status_changed_at' => null,
        ]);

        // Mirrors the backfill loop of the migration, against rows that predate it.
        DB::table('sourcing_orders')
            ->select('id', 'status', 'status_timestamps', 'updated_at')
            ->orderBy('id')
            ->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    $timestamps = json_decode((string) ($row->status_timestamps ?? 'null'), true);
                    $backfilled = is_array($timestamps) && isset($timestamps[$row->status])
                        ? $timestamps[$row->status]
                        : $row->updated_at;

                    DB::table('sourcing_orders')
                        ->where('id', $row->id)
                        ->update(['status_changed_at' => $backfilled]);
                }
            });

        $this->assertSame(
            $timestamp,
            $order->fresh()->status_changed_at->format('Y-m-d H:i:s')
        );
    }

    /** @test */
    public function dashboard_shows_the_sla_banner_when_orders_are_restricted(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $this->makeOrder($client, 'paid', $admin, [
            'is_restricted_due_to_delay' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Action needed on your folders');
    }

    /** @test */
    public function the_orders_index_overdue_filter_returns_only_restricted_orders(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $clientA = User::factory()->create(['role' => 'client', 'name' => 'Restricted Client Zulu']);
        $clientB = User::factory()->create(['role' => 'client', 'name' => 'Normal Client Alpha']);
        $this->makeOrder($clientA, 'paid', $admin, [
            'is_restricted_due_to_delay' => true,
        ]);
        $this->makeOrder($clientB, 'paid', $admin);

        $this->actingAs($admin)
            ->get(route('admin.sourcing-orders.index', ['status' => 'paid', 'overdue' => 1]))
            ->assertOk()
            ->assertSee('Restricted Client Zulu')
            ->assertDontSee('Normal Client Alpha');
    }

    /** @test */
    public function the_overdue_filter_returns_only_restricted_requests(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        $restricted = $this->makeRequest($client, 'in_review', $admin, [
            'is_restricted_due_to_delay' => true,
        ]);
        $normal = $this->makeRequest($client, 'in_review', $admin);

        $this->actingAs($admin)
            ->get(route('admin.sourcing-requests.index', ['status' => 'in_review', 'overdue' => 1]))
            ->assertOk()
            ->assertSee($restricted->product_name)
            ->assertDontSee($normal->product_name);
    }
}
