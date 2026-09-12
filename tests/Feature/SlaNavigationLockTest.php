<?php

namespace Tests\Feature;

use App\Models\FeatureFlag;
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

        $response->assertSee('Navigation restricted (SLA)');
        $response->assertSee(route('admin.quotations.create', ['sourcingRequest' => $restricted]));
        $response->assertDontSee('Quoted');
        $response->assertDontSee('Negotiating');
        $response->assertDontSee('Accepted');
        $response->assertSee('In Review');
    }
}