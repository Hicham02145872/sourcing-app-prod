<?php

namespace Tests\Feature;

use App\Events\SourcingOrderStatusChanged;
use App\Listeners\SyncOrderToLarkSheet;
use App\Models\ShippingCompany;
use App\Models\SourcingOrder;
use App\Models\User;
use App\Services\LarkSheetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class SyncOrderToLarkSheetTest extends TestCase
{
    use RefreshDatabase;

    public function test_listener_is_triggered_when_sourcing_order_status_changes_to_shipment_preparing()
    {
        Event::fake();

        $user = User::factory()->create();
        $order = SourcingOrder::factory()->create([
            'status' => 'paid',
            'user_id' => $user->id,
        ]);

        $order->update(['status' => 'shipment_preparing']);

        Event::assertDispatched(SourcingOrderStatusChanged::class, function ($event) use ($order) {
            return $event->sourcingOrder->id === $order->id;
        });
    }

    public function test_listener_calls_service_when_conditions_met()
    {
        $company = ShippingCompany::first() ?? ShippingCompany::factory()->create();

        $company->update([
            'lark_app_id' => 'test_app_id',
            'lark_app_secret' => 'test_secret',
            'lark_base_token' => 'test_base',
            'lark_table_id' => 'test_table',
        ]);

        $order = SourcingOrder::factory()->create([
            'status' => 'shipment_preparing',
            'shipping_company_id' => $company->id,
        ]);

        $this->mock(LarkSheetService::class, function (MockInterface $mock) use ($order, $company) {
            $mock->shouldReceive('syncOrder')
                ->once()
                ->with(
                    Mockery::on(fn ($arg) => $arg->id === $order->id),
                    Mockery::on(fn ($arg) => $arg->id === $company->id)
                )
                ->andReturn(true);
        });

        $listener = app(SyncOrderToLarkSheet::class);
        $event = new SourcingOrderStatusChanged($order, 'paid');
        $listener->handle($event);
    }

    public function test_listener_does_not_call_service_if_no_company_or_config()
    {
        $order = SourcingOrder::factory()->create([
            'status' => 'shipment_preparing',
            'shipping_company_id' => null,
        ]);

        $this->mock(LarkSheetService::class, function (MockInterface $mock) {
            $mock->shouldNotReceive('syncOrder');
        });

        $listener = app(SyncOrderToLarkSheet::class);
        $event = new SourcingOrderStatusChanged($order, 'paid');
        $listener->handle($event);
    }
}
