<?php

namespace Tests\Feature;

use App\Exceptions\WorkflowLimitReachedException;
use App\Livewire\Admin\SourcingRequestWorkflow;
use App\Models\FeatureFlag;
use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WorkflowInReviewLimitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function enableInReviewLimitFlag(): void
    {
        FeatureFlag::create([
            'key' => 'workflow_in_review_limit',
            'name' => 'In-review workflow limit',
            'status' => 'visible',
        ]);
    }

    private function disableInReviewLimitFlag(): void
    {
        FeatureFlag::create([
            'key' => 'workflow_in_review_limit',
            'name' => 'In-review workflow limit',
            'status' => 'hidden',
        ]);
    }

    private function requestPendingForClient(array $overrides = []): SourcingRequest
    {
        return SourcingRequest::factory()->create(array_merge([
            'user_id' => User::factory()->create(['role' => 'client'])->id,
            'status' => 'pending',
            'assigned_to_admin_id' => null,
            'assigned_at' => null,
        ], $overrides));
    }

    private function inReviewForAdmin(User $admin): SourcingRequest
    {
        return SourcingRequest::factory()->create([
            'user_id' => User::factory()->create(['role' => 'client'])->id,
            'status' => 'in_review',
            'assigned_to_admin_id' => $admin->id,
            'assigned_at' => now(),
        ]);
    }

    /** @test */
    public function an_admin_with_four_in_review_can_claim_a_fifth(): void
    {
        $this->enableInReviewLimitFlag();
        $admin = User::factory()->create(['role' => 'admin']);

        collect(range(1, 4))->each(fn () => $this->inReviewForAdmin($admin));
        $pending = $this->requestPendingForClient();

        Livewire::actingAs($admin)
            ->test(SourcingRequestWorkflow::class, ['sourcingRequest' => $pending])
            ->call('updateStatus', 'in_review');

        $this->assertSame('in_review', $pending->fresh()->status);
        $this->assertSame(5, SourcingRequest::where('assigned_to_admin_id', $admin->id)->where('status', 'in_review')->count());
    }

    /** @test */
    public function an_admin_holding_five_is_blocked_on_the_sixth_request(): void
    {
        $this->enableInReviewLimitFlag();
        $admin = User::factory()->create(['role' => 'admin']);

        collect(range(1, 5))->each(fn () => $this->inReviewForAdmin($admin));
        $pending = $this->requestPendingForClient();

        Livewire::actingAs($admin)
            ->test(SourcingRequestWorkflow::class, ['sourcingRequest' => $pending])
            ->call('updateStatus', 'in_review')
            ->assertDispatched('workflow-limit-reached', function ($event, $params) {
                return ($params['code'] ?? null) === WorkflowLimitReachedException::ERROR_CODE;
            })
            ->assertDispatched('show-error-toast');

        $this->assertSame('pending', $pending->fresh()->status);
    }

    /** @test */
    public function super_admin_above_the_limit_can_still_claim(): void
    {
        $this->enableInReviewLimitFlag();
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        collect(range(1, 6))->each(fn () => $this->inReviewForAdmin($superAdmin));
        $pending = $this->requestPendingForClient();

        Livewire::actingAs($superAdmin)
            ->test(SourcingRequestWorkflow::class, ['sourcingRequest' => $pending])
            ->call('updateStatus', 'in_review');

        $this->assertSame('in_review', $pending->fresh()->status);
    }

    /** @test */
    public function limit_is_not_enforced_when_the_flag_is_disabled(): void
    {
        $this->disableInReviewLimitFlag();
        $admin = User::factory()->create(['role' => 'admin']);

        collect(range(1, 6))->each(fn () => $this->inReviewForAdmin($admin));
        $pending = $this->requestPendingForClient();

        Livewire::actingAs($admin)
            ->test(SourcingRequestWorkflow::class, ['sourcingRequest' => $pending])
            ->call('updateStatus', 'in_review');

        $this->assertSame('in_review', $pending->fresh()->status);
    }

    /** @test */
    public function dashboard_shows_the_persistent_banner_at_the_limit(): void
    {
        $this->enableInReviewLimitFlag();
        $admin = User::factory()->create(['role' => 'admin']);

        collect(range(1, 5))->each(fn () => $this->inReviewForAdmin($admin));

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('View my pending requests');
    }

    /** @test */
    public function dashboard_hides_the_banner_below_the_limit(): void
    {
        $this->enableInReviewLimitFlag();
        $admin = User::factory()->create(['role' => 'admin']);

        collect(range(1, 2))->each(fn () => $this->inReviewForAdmin($admin));

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('View my pending requests');
    }

    /** @test */
    public function admin_id_me_filters_the_in_review_list_to_the_current_admin(): void
    {
        $this->enableInReviewLimitFlag();
        $admin = User::factory()->create(['role' => 'admin']);
        $otherAdmin = User::factory()->create(['role' => 'admin']);

        $mine = $this->inReviewForAdmin($admin);
        $other = $this->inReviewForAdmin($otherAdmin);

        $this->actingAs($admin)
            ->get(route('admin.sourcing-requests.index', ['status' => 'in_review', 'admin_id' => 'me']))
            ->assertOk()
            ->assertSee($mine->product_name)
            ->assertDontSee($other->product_name);
    }
}