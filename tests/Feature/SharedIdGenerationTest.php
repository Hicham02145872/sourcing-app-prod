<?php

namespace Tests\Feature;

use App\Http\Controllers\Client\QuotationController;
use App\Models\Category;
use App\Models\Quotation;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\User;
use App\Services\SharedIdService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SharedIdGenerationTest extends TestCase
{
    use RefreshDatabase;

    protected function createQuotedRequestWithQuotation(): array
    {
        $client = User::factory()->create(['role' => 'client']);
        $admin = User::factory()->create(['role' => 'admin']);

        $sourcingRequest = SourcingRequest::factory()->create([
            'user_id' => $client->id,
            'assigned_to_admin_id' => $admin->id,
            'status' => 'quoted',
        ]);

        $quotation = Quotation::factory()->create([
            'sourcing_request_id' => $sourcingRequest->id,
            'status' => 'sent',
            'amount' => 200.00,
        ]);

        return compact('client', 'admin', 'sourcingRequest', 'quotation');
    }

    public function test_nouvelle_sr_obtient_SB00005(): void
    {
        $sr = SourcingRequest::factory()->create();

        $this->assertSame('SB00005', $sr->shared_id);
        $this->assertSame('SB00005', $sr->reference_id);
    }

    public function test_deuxieme_sr_obtient_SB00010(): void
    {
        SourcingRequest::factory()->create();
        $sr2 = SourcingRequest::factory()->create();

        $this->assertSame('SB00010', $sr2->shared_id);
    }

    public function test_so_partage_shared_id_de_la_sr(): void
    {
        $data = $this->createQuotedRequestWithQuotation();

        $this->actingAs($data['client']);
        app(QuotationController::class)->accept(Request::create('/', 'POST'), 'eng', $data['quotation']);

        $order = SourcingOrder::where('quotation_id', $data['quotation']->id)->first();

        $this->assertNotNull($order);
        $this->assertSame($data['sourcingRequest']->shared_id, $order->shared_id);
        $this->assertSame($data['sourcingRequest']->reference_id, $order->reference_id);
    }

    public function test_so_a_sourcing_request_id_lie(): void
    {
        $data = $this->createQuotedRequestWithQuotation();

        $this->actingAs($data['client']);
        app(QuotationController::class)->accept(Request::create('/', 'POST'), 'eng', $data['quotation']);

        $order = SourcingOrder::where('quotation_id', $data['quotation']->id)->first();

        $this->assertSame($data['sourcingRequest']->id, $order->sourcing_request_id);
    }

    public function test_pas_de_deuxieme_so_pour_meme_sr(): void
    {
        $data = $this->createQuotedRequestWithQuotation();

        $this->actingAs($data['client']);
        $controller = app(QuotationController::class);
        $request = Request::create('/', 'POST');

        $controller->accept($request, 'eng', $data['quotation']);
        $firstOrderId = SourcingOrder::where('quotation_id', $data['quotation']->id)->value('id');

        $controller->accept($request, 'eng', $data['quotation']->fresh());
        $orderCount = SourcingOrder::where('quotation_id', $data['quotation']->id)->count();

        $this->assertSame(1, $orderCount);
        $this->assertSame($firstOrderId, SourcingOrder::where('quotation_id', $data['quotation']->id)->value('id'));
    }

    public function test_ancienne_sr_sans_shared_id_reste_intacte(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $category = Category::factory()->create();

        $legacyId = DB::table('sourcing_requests')->insertGetId([
            'user_id' => $client->id,
            'product_name' => 'Legacy product',
            'category_id' => $category->id,
            'status' => 'pending',
            'shared_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sr = SourcingRequest::findOrFail($legacyId);

        $this->assertNull($sr->shared_id);
        $this->assertSame('#'.($legacyId * 5), $sr->reference_id);
    }

    public function test_ancien_so_sans_shared_id_reste_intact(): void
    {
        $data = $this->createQuotedRequestWithQuotation();

        $legacyOrderId = DB::table('sourcing_orders')->insertGetId([
            'user_id' => $data['client']->id,
            'quotation_id' => $data['quotation']->id,
            'sourcing_request_id' => $data['sourcingRequest']->id,
            'total_amount' => 99.99,
            'status' => 'pending_payment',
            'shared_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $order = SourcingOrder::findOrFail($legacyOrderId);

        $this->assertNull($order->shared_id);
        $this->assertSame('#'.($legacyOrderId * 5), $order->reference_id);
    }

    public function test_recherche_par_shared_id_trouve_la_sr(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $sr = SourcingRequest::factory()->create(['shared_id' => 'SB00005']);

        $response = $this->actingAs($admin)->get(route('admin.sourcing-requests.index', ['search' => 'SB00005']));

        $response->assertOk();
        $response->assertSee('SB00005', false);
        $response->assertSee($sr->product_name, false);
    }

    public function test_creation_so_n_incremente_pas_le_compteur(): void
    {
        $data = $this->createQuotedRequestWithQuotation();
        $counterBefore = app(SharedIdService::class)->currentCounter();

        $this->actingAs($data['client']);
        app(QuotationController::class)->accept(Request::create('/', 'POST'), 'eng', $data['quotation']);

        $counterAfter = app(SharedIdService::class)->currentCounter();

        $this->assertSame($counterBefore, $counterAfter);
        $this->assertSame('SB00005', $data['sourcingRequest']->fresh()->shared_id);
    }
}
