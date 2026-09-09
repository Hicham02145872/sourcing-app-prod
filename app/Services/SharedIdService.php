<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SharedIdService
{
    public function generate(): string
    {
        return DB::transaction(function () {
            $row = DB::table('sb_id_sequence')->lockForUpdate()->first();

            if (! $row) {
                DB::table('sb_id_sequence')->insert(['last_value' => 0]);
                $row = DB::table('sb_id_sequence')->lockForUpdate()->first();
            }

            $next = ((int) $row->last_value) + 5;
            DB::table('sb_id_sequence')->update(['last_value' => $next]);

            return self::format($next);
        });
    }

    public static function format(int $counter): string
    {
        return 'FSB'.str_pad((string) $counter, 6, '0', STR_PAD_LEFT);
    }

    public static function parse(string $sharedId): ?int
    {
        $normalized = strtoupper(trim($sharedId));

        if (preg_match('/^FSB(\d{6})$/', $normalized, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/^SB(\d{5})$/', $normalized, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    public function currentCounter(): int
    {
        $row = DB::table('sb_id_sequence')->first();

        return (int) ($row->last_value ?? 0);
    }
}
