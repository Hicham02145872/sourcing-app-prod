<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Country;
use App\Models\Quotation;
use App\Models\Service;
use App\Models\SourcingRequest;
use App\Models\User;
use App\Services\QuotationAmountRecalculationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuotationAmountRecalculationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_delivery_cost_scales_with_total_quantity(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $country = Country::factory()->create();
        $service = Service::factory()->create();
        $sr = SourcingRequest::factory()->create([
            'user_id' => $user->id,
            'category_id' => Category::factory(),
        ]);
        $sr->destinations()->create([
            'country_id' => $country->id,
            'service_id' => $service->id,
            'quantity' => 100,
            'address' => 'Test address',
        ]);

        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $sr->id,
            'unit_price' => 2,
            'commission_service' => 10,
            'delivery_cost_china' => 50,
            'amount' => 260,
        ]);

        $sr->destinations()->first()->update(['quantity' => 50]);

        app(QuotationAmountRecalculationService::class)->recalculate($quotation->fresh(), 100);

        $quotation->refresh();

        $this->assertEquals(25.0, (float) $quotation->delivery_cost_china);
        $this->assertEquals(135.0, (float) $quotation->amount);
    }
}
