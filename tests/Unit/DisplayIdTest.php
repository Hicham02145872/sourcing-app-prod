<?php

namespace Tests\Unit;

use App\Models\Quotation;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DisplayIdTest extends TestCase
{
    use RefreshDatabase;

    public function test_sourcing_request_display_id_is_multiple_of_5()
    {
        $request1 = new SourcingRequest(['id' => 1]);
        $request2 = new SourcingRequest(['id' => 2]);
        $request3 = new SourcingRequest(['id' => 3]);

        $this->assertEquals(5, $request1->display_id);
        $this->assertEquals(10, $request2->display_id);
        $this->assertEquals(15, $request3->display_id);
    }

    public function test_quotation_display_id_is_multiple_of_5()
    {
        $quotation1 = new Quotation(['id' => 1]);
        $quotation2 = new Quotation(['id' => 2]);

        $this->assertEquals(5, $quotation1->display_id);
        $this->assertEquals(10, $quotation2->display_id);
    }

    public function test_sourcing_order_display_id_is_multiple_of_5()
    {
        $order1 = new SourcingOrder(['id' => 1]);
        $order2 = new SourcingOrder(['id' => 2]);

        $this->assertEquals(5, $order1->display_id);
        $this->assertEquals(10, $order2->display_id);
    }
}
