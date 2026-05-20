<?php

namespace Tests\Unit;

use App\Services\SharedIdService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SharedIdServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_first_id_is_SB00005(): void
    {
        $service = app(SharedIdService::class);

        $this->assertSame('SB00005', $service->generate());
    }

    public function test_generate_second_id_is_SB00010(): void
    {
        $service = app(SharedIdService::class);

        $service->generate();

        $this->assertSame('SB00010', $service->generate());
    }

    public function test_generate_increments_by_five_over_many_calls(): void
    {
        $service = app(SharedIdService::class);
        $last = null;

        for ($i = 0; $i < 1000; $i++) {
            $last = $service->generate();
        }

        $this->assertSame('SB05000', $last);
        $this->assertSame(5000, $service->currentCounter());
    }

    public function test_format_and_parse_helpers(): void
    {
        $this->assertSame('SB00050', SharedIdService::format(50));
        $this->assertSame(50, SharedIdService::parse('SB00050'));
        $this->assertNull(SharedIdService::parse('invalid'));
        $this->assertSame(50, SharedIdService::parse('sb00050'));
    }
}
