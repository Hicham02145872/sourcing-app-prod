<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Country;
use App\Models\Quotation;
use App\Models\Service;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\SourcingRequestDestination;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SlaE2ECommand extends Command
{
    protected $signature = 'sla:e2e {action : seed|reset|flag|state} {--scenario=all : in_review|negotiating|paid|china|all}';

    protected $description = 'Create/reset SLA workflow end-to-end fixtures for the four SLA rules.';

    private const ADMIN_EMAIL = 'sla.e2e@example.com';

    private const CLIENT_EMAIL = 'sla.e2e.client@example.com';

    private const SCENARIO_MARKERS = [
        'in_review' => 'in_review',
        'negotiating' => 'negotiating',
        'paid' => 'req-paid',
        'china' => 'req-china',
    ];

    public function handle(): int
    {
        return match ($this->argument('action')) {
            'seed' => $this->seed(),
            'reset' => $this->reset(),
            'flag' => $this->flag(),
            'state' => $this->state(),
            default => $this->error("Unknown action '{$this->argument('action')}'."),
        };
    }

    protected function seed(): int
    {
        $admin = $this->ensureUser(self::ADMIN_EMAIL, 'SLA E2E Admin', 'admin');
        $client = $this->ensureUser(self::CLIENT_EMAIL, 'SLA E2E Client', 'client');
        $category = Category::first();
        $country = Country::first();
        $service = Service::first();

        if (! $category || ! $country || ! $service) {
            $this->error('Missing reference data (category/country/service) — aborting.');

            return self::FAILURE;
        }

        $inReview = $this->ensureRequest($admin, $client, $category->id, 'in_review', 'in_review', 26);
        $this->ensureDestination($inReview, $country->id, $service->id);

        $negotiating = $this->ensureRequest($admin, $client, $category->id, 'negotiating', 'negotiating', 26);
        $this->ensureDestination($negotiating, $country->id, $service->id);
        $this->ensureQuotation($negotiating, 'pending');

        $paidReq = $this->ensureRequest($admin, $client, $category->id, 'req-paid', 'accepted', 0);
        $this->ensureDestination($paidReq, $country->id, $service->id);
        $this->ensureQuotation($paidReq, 'accepted');
        $this->ensureOrder($client, 'paid', $paidReq, 26);

        $chinaReq = $this->ensureRequest($admin, $client, $category->id, 'req-china', 'accepted', 0);
        $this->ensureDestination($chinaReq, $country->id, $service->id);
        $this->ensureQuotation($chinaReq, 'accepted');
        $this->ensureOrder($client, 'in_transit_china', $chinaReq, 50);

        $this->info('SLA e2e fixtures ready.');
        $this->state();

        return self::SUCCESS;
    }

    protected function reset(): int
    {
        $scenario = $this->option('scenario');

        if ($scenario !== 'all' && ! in_array($scenario, array_keys(self::SCENARIO_MARKERS), true)) {
            $this->error("Unknown scenario '{$scenario}'.");

            return self::FAILURE;
        }

        // `resetScenario('all')` restores EVERY fixture to its pre-flag state;
        // otherwise it restores only the requested scenario.
        $this->resetScenario($scenario);

        return self::SUCCESS;
    }

    protected function resetScenario(string $scenario): void
    {
        // Restore every fixture to a state where ONLY the target scenario is
        // "pre-flag" (in an SLA rule status, un-restricted, past its deadline).
        // All other scenarios are moved to a resolved status (outside the rules)
        // so the deadline check flags exactly one item at a time.
        $this->resetRequestScenario('in_review', $scenario);
        $this->resetRequestScenario('negotiating', $scenario);
        $this->resetOrderScenario('paid', $scenario, 'paid', 26);
        $this->resetOrderScenario('china', $scenario, 'in_transit_china', 50);
    }

    protected function resetRequestScenario(string $name, string $scenario): void
    {
        $request = $this->requestForMarker($name);
        if (! $request) {
            return;
        }

        if ($name === $scenario || $scenario === 'all') {
            if ($name === 'negotiating' && ! $request->quotation) {
                $this->ensureQuotation($request, 'pending');
            }
            if ($name === 'in_review' && $request->quotation) {
                $request->quotation->delete();
            }
            DB::table('sourcing_requests')->where('id', $request->id)->update([
                'status' => $name,
                'status_changed_at' => now()->subHours(26),
                'is_restricted_due_to_delay' => false,
            ]);
        } else {
            DB::table('sourcing_requests')->where('id', $request->id)->update([
                'status' => 'quoted',
                'is_restricted_due_to_delay' => false,
            ]);
        }
    }

    protected function resetOrderScenario(string $name, string $scenario, string $ruleStatus, int $hours): void
    {
        $request = $this->requestForMarker(self::SCENARIO_MARKERS[$name]);
        $order = $request?->order;
        if (! $order) {
            return;
        }

        if ($name === $scenario || $scenario === 'all') {
            DB::table('sourcing_orders')->where('id', $order->id)->update([
                'status' => $ruleStatus,
                'status_changed_at' => now()->subHours($hours),
                'is_restricted_due_to_delay' => false,
                'tracking_number' => null,
                'tracking_carrier' => null,
                'china_tracking_number' => null,
                'parcel_photo_path' => null,
                'parcel_photo_public_id' => null,
                'parcel_weight_kg' => null,
                'parcel_photo_uploaded_at' => null,
                'package_label_photo_path' => null,
            ]);
        } else {
            DB::table('sourcing_orders')->where('id', $order->id)->update([
                'status' => $name === 'paid' ? 'shipment_preparing' : 'arrival_uae',
                'is_restricted_due_to_delay' => false,
            ]);
        }
    }

    protected function flag(): int
    {
        Artisan::call('workflow:check-deadlines');
        $this->info(Artisan::output());

        return self::SUCCESS;
    }

    protected function state(): int
    {
        $admin = User::where('email', self::ADMIN_EMAIL)->first();
        $out = ['admin_id' => $admin?->id];

        foreach ([
            'in_review' => 'in_review',
            'negotiating' => 'negotiating',
            'paid' => 'req-paid',
            'china' => 'req-china',
        ] as $key => $marker) {
            $request = $this->requestForMarker($marker);
            $item = [
                'request_id' => $request?->id,
                'request_status' => $request?->status,
                'request_restricted' => (bool) ($request->is_restricted_due_to_delay ?? false),
                'quotation_id' => $request?->quotation?->id,
            ];
            if ($request?->order) {
                $item['order_id'] = $request->order->id;
                $item['order_status'] = $request->order->status;
                $item['order_restricted'] = (bool) $request->order->is_restricted_due_to_delay;
                $item['parcel_uploaded'] = (bool) $request->order->parcel_photo_uploaded_at;
                $item['china_tracking'] = (bool) $request->order->tracking_number;
            }
            $out[$key] = $item;
        }

        $this->line(json_encode($out));

        return self::SUCCESS;
    }

    protected function ensureUser(string $email, string $name, string $role): User
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            ['name' => $name, 'role' => $role, 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );

        if ($user->email_verified_at === null) {
            $user->forceFill(['email_verified_at' => now()])->saveQuietly();
        }

        return $user;
    }

    protected function ensureRequest(User $admin, User $client, int $categoryId, string $marker, string $status, int $backdateHours): SourcingRequest
    {
        $request = $this->requestForMarker($marker);

        if (! $request) {
            return SourcingRequest::create([
                'user_id' => $client->id,
                'product_name' => "[SLA-E2E] {$marker}",
                'product_url' => 'https://example.com/sla-e2e',
                'category_id' => $categoryId,
                'shipping_method' => 'air',
                'sourcing_location' => 'china',
                'status' => $status,
                'assigned_to_admin_id' => $admin->id,
                'assigned_at' => now(),
                'status_changed_at' => now()->subHours($backdateHours),
                'is_restricted_due_to_delay' => false,
            ]);
        }

        return $request;
    }

    protected function ensureDestination(SourcingRequest $request, int $countryId, int $serviceId): void
    {
        SourcingRequestDestination::firstOrCreate(
            ['sourcing_request_id' => $request->id, 'country_id' => $countryId],
            ['service_id' => $serviceId, 'quantity' => 1, 'address' => 'Dakar, Senegal']
        );
    }

    protected function ensureQuotation(SourcingRequest $request, string $status): Quotation
    {
        if ($request->quotation) {
            return $request->quotation;
        }

        $request->unsetRelation('quotation');

        return $request->quotation()->create([
            'assigned_to_admin_id' => $request->assigned_to_admin_id,
            'amount' => 0,
            'unit_price' => 10,
            'commission_service' => 0,
            'delivery_cost_china' => 0,
            'currency' => 'USD',
            'status' => $status,
        ]);
    }

    protected function ensureOrder(User $client, string $status, SourcingRequest $request, int $backdateHours): SourcingOrder
    {
        if ($request->order) {
            return $request->order;
        }

        $request->unsetRelation('order');

        $quotation = $request->quotation()->first();

        return $request->order()->create([
            'user_id' => $client->id,
            'quotation_id' => $quotation->id,
            'sourcing_request_id' => $request->id,
            'total_amount' => 0,
            'status' => $status,
            'assigned_to_admin_id' => $request->assigned_to_admin_id,
            'status_changed_at' => now()->subHours($backdateHours),
            'is_restricted_due_to_delay' => false,
        ]);
    }

    protected function requestForMarker(string $marker): ?SourcingRequest
    {
        return SourcingRequest::with('order', 'quotation')
            ->where('product_name', "[SLA-E2E] {$marker}")
            ->first();
    }
}