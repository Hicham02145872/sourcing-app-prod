<?php

namespace Tests\Feature;

use App\Models\FeatureFlag;
use App\Models\Quotation;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlaNavigationLockTest extends TestCase
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

    private function restrictAdmin(User $admin): SourcingRequest
    {
        $client = User::factory()->create(['role' => 'client']);

        return SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'in_review',
            'assigned_to_admin_id' => $admin->id,
            'assigned_at' => now()->subDays(2),
            'is_restricted_due_to_delay' => true,
        ]);
    }

    private function restrictAdminNegotiating(User $admin): SourcingRequest
    {
        $client = User::factory()->create(['role' => 'client']);

        return SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'status' => 'negotiating',
            'assigned_to_admin_id' => $admin->id,
            'assigned_at' => now()->subDays(2),
            'is_restricted_due_to_delay' => true,
        ]);
    }

    private function restrictAdminOrder(User $admin, string $status, array $overrides = []): SourcingOrder
    {
        return SourcingOrder::factory()->create(array_merge([
            'assigned_to_admin_id' => $admin->id,
            'status' => $status,
            'status_changed_at' => now()->subDays(2),
            'is_restricted_due_to_delay' => true,
        ], $overrides));
    }

    /** @test */
    public function a_locked_admin_is_redirected_to_the_quotation_creation_page_when_opening_a_blocked_page(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $restricted = $this->restrictAdmin($admin);

        $this->actingAs($admin)
            ->get(route('admin.sourcing-orders.index'))
            ->assertRedirect(route('admin.quotations.create', ['sourcingRequest' => $restricted]));
    }

    /** @test */
    public function a_locked_admin_is_redirected_from_quotations_list_and_management_pages(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $restricted = $this->restrictAdmin($admin);

        $this->actingAs($admin)
            ->get(route('admin.quotations.index'))
            ->assertRedirect(route('admin.quotations.create', ['sourcingRequest' => $restricted]));

        $this->actingAs($admin)
            ->get(route('admin.social-media-links.edit'))
            ->assertRedirect(route('admin.quotations.create', ['sourcingRequest' => $restricted]));
    }

    /** @test */
    public function a_locked_admin_can_still_open_the_dashboard_and_requests_pages(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $this->restrictAdmin($admin);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.sourcing-requests.index', ['status' => 'all']))
            ->assertOk();
    }

    /** @test */
    public function a_locked_admin_can_open_the_quotation_creation_page_for_their_restricted_request(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $restricted = $this->restrictAdmin($admin);

        $this->actingAs($admin)
            ->get(route('admin.quotations.create', ['sourcingRequest' => $restricted]))
            ->assertOk();
    }

    /** @test */
    public function an_admin_without_restricted_requests_keeps_full_navigation(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.sourcing-orders.index'))
            ->assertOk();
    }

    /** @test */
    public function an_admin_is_not_locked_when_another_admin_has_the_restricted_request(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $otherAdmin = User::factory()->create(['role' => 'admin']);
        $this->restrictAdmin($otherAdmin);

        $this->actingAs($admin)
            ->get(route('admin.sourcing-orders.index'))
            ->assertOk();
    }

    /** @test */
    public function a_super_admin_is_never_locked(): void
    {
        $this->enableSlaFlag();
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->restrictAdmin($superAdmin);

        $this->actingAs($superAdmin)
            ->get(route('admin.sourcing-orders.index'))
            ->assertOk();
    }

    /** @test */
    public function the_lock_does_not_apply_when_the_feature_flag_is_disabled(): void
    {
        $this->disableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $this->restrictAdmin($admin);

        $this->actingAs($admin)
            ->get(route('admin.sourcing-orders.index'))
            ->assertOk();
    }

    /** @test */
    public function a_locked_admin_can_still_open_a_specific_request_and_its_actions(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $request = $this->restrictAdmin($admin);

        $this->actingAs($admin)
            ->get(route('admin.sourcing-requests.show', $request))
            ->assertOk();
    }

    /** @test */
    public function unlocked_pages_outside_the_admin_group_are_never_redirected(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $this->restrictAdmin($admin);

        $this->actingAs($admin)
            ->get(route('profile.edit'))
            ->assertOk();
    }

    /** @test */
    public function the_locked_requests_page_hides_other_status_tabs_and_shows_the_lock_banner(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Locked Admin']);
        $restricted = $this->restrictAdmin($admin);

        $response = $this->actingAs($admin)
            ->get(route('admin.sourcing-requests.index'))
            ->assertOk();

        $response->assertSee('Action needed on your folders');
        $response->assertSee(route('admin.quotations.create', ['sourcingRequest' => $restricted]));
        $response->assertDontSee('Quoted');
        $response->assertDontSee('Negotiating');
        $response->assertDontSee('Accepted');
        $response->assertSee('In Review');
    }

    /** @test */
    public function a_locked_admin_with_an_overdue_negotiating_request_is_redirected_to_the_quotation_edit_page(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $restricted = $this->restrictAdminNegotiating($admin);
        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $restricted->id,
            'assigned_to_admin_id' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.social-media-links.edit'))
            ->assertRedirect(route('admin.quotations.edit', $quotation));
    }

    /** @test */
    public function a_locked_admin_with_an_overdue_negotiating_request_without_a_quotation_keeps_the_request_page_target(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $restricted = $this->restrictAdminNegotiating($admin);

        $this->actingAs($admin)
            ->get(route('admin.social-media-links.edit'))
            ->assertRedirect(route('admin.sourcing-requests.show', $restricted));
    }

    /** @test */
    public function a_locked_admin_with_an_overdue_negotiating_request_can_open_the_quotation_edit_page(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $restricted = $this->restrictAdminNegotiating($admin);
        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $restricted->id,
            'assigned_to_admin_id' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.quotations.edit', $quotation))
            ->assertOk();
    }

    /** @test */
    public function a_locked_admin_with_an_overdue_paid_order_is_redirected_to_the_order_page(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->restrictAdminOrder($admin, 'paid');

        $this->actingAs($admin)
            ->get(route('admin.quotations.index'))
            ->assertRedirect(route('admin.sourcing-orders.show', $order));
    }

    /** @test */
    public function a_locked_admin_with_an_overdue_in_transit_china_order_is_redirected_to_the_order_page(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->restrictAdminOrder($admin, 'in_transit_china');

        $this->actingAs($admin)
            ->get(route('admin.quotations.index'))
            ->assertRedirect(route('admin.sourcing-orders.show', $order));
    }

    /** @test */
    public function an_admin_locked_by_an_overdue_order_can_open_the_orders_index_and_the_dashboard(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Order Locked Admin']);
        $this->restrictAdminOrder($admin, 'paid');

        $this->actingAs($admin)
            ->get(route('admin.sourcing-orders.index', ['status' => 'all']))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    /** @test */
    public function an_admin_locked_by_an_overdue_order_sees_only_the_locked_order_status_tabs(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Order Locked Admin']);
        $this->restrictAdminOrder($admin, 'paid');

        $response = $this->actingAs($admin)
            ->get(route('admin.sourcing-orders.index'))
            ->assertOk();

        $response->assertSee('Action needed on your folders');
        $response->assertSee('Paid');
        $response->assertDontSee('In Transit (CN)');
        $response->assertDontSee('Shipment Preparing');
    }

    /** @test */
    public function an_admin_locked_only_by_a_request_is_redirected_away_from_the_orders_index(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $restricted = $this->restrictAdmin($admin);

        $this->actingAs($admin)
            ->get(route('admin.sourcing-orders.index'))
            ->assertRedirect(route('admin.quotations.create', ['sourcingRequest' => $restricted]));
    }

    /** @test */
    public function an_in_review_request_wins_over_a_paid_order_when_both_are_overdue(): void
    {
        $this->enableSlaFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $restricted = $this->restrictAdmin($admin);
        $this->restrictAdminOrder($admin, 'paid');

        $this->actingAs($admin)
            ->get(route('admin.quotations.index'))
            ->assertRedirect(route('admin.quotations.create', ['sourcingRequest' => $restricted]));
    }
}
