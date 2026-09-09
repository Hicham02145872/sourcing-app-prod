<?php

namespace Tests\Unit;

use App\Services\SharedIdService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SharedIdServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_first_id_is_FSB000005(): void
    {
        $service = app(SharedIdService::class);

        $this->assertSame('FSB000005', $service->generate());
    }

    public function test_generate_second_id_is_FSB000010(): void
    {
        $service = app(SharedIdService::class);

        $service->generate();

        $this->assertSame('FSB000010', $service->generate());
    }

    public function test_generate_increments_by_five_over_many_calls(): void
    {
        $service = app(SharedIdService::class);
        $last = null;

        for ($i = 0; $i < 1000; $i++) {
            $last = $service->generate();
        }

        $this->assertSame('FSB005000', $last);
        $this->assertSame(5000, $service->currentCounter());
    }

    public function test_format_and_parse_helpers(): void
    {
        $this->assertSame('FSB000050', SharedIdService::format(50));
        $this->assertSame(50, SharedIdService::parse('FSB000050'));
        $this->assertNull(SharedIdService::parse('invalid'));
        $this->assertSame(50, SharedIdService::parse('fsb000050'));
    }

    public function test_parse_accepts_legacy_sb_format(): void
    {
        $this->assertSame(50, SharedIdService::parse('SB00050'));
        $this->assertSame(50, SharedIdService::parse('sb00050'));
        $this->assertNull(SharedIdService::parse('SB000505'));
        $this->assertNull(SharedIdService::parse('FSB0000500'));
    }
}