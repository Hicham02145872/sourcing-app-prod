<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sourcing_requests', function (Blueprint $table) {
            $table->timestamp('status_changed_at')->nullable()->after('status_timestamps');
            $table->boolean('is_restricted_due_to_delay')->default(false)->after('status_changed_at');
        });

        // Backfill status_changed_at from the recorded status timestamp, else updated_at.
        DB::table('sourcing_requests')
            ->select('id', 'status', 'status_timestamps', 'updated_at')
            ->orderBy('id')
            ->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    $timestamp = null;
                    $timestamps = json_decode((string) ($row->status_timestamps ?? 'null'), true);
                    if (is_array($timestamps) && isset($timestamps[$row->status])) {
                        $timestamp = $timestamps[$row->status];
                    } else {
                        $timestamp = $row->updated_at;
                    }

                    DB::table('sourcing_requests')
                        ->where('id', $row->id)
                        ->update(['status_changed_at' => $timestamp]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('sourcing_requests', function (Blueprint $table) {
            $table->dropColumn(['status_changed_at', 'is_restricted_due_to_delay']);
        });
    }
};