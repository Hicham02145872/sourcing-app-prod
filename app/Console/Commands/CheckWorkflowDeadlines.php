<?php

namespace App\Console\Commands;

use App\Models\SourcingRequest;
use App\Notifications\SlaDeadlineExceeded;
use App\Services\FeatureFlagService;
use Illuminate\Console\Command;

class CheckWorkflowDeadlines extends Command
{
    protected $signature = 'workflow:check-deadlines';

    protected $description = 'Flag requests that exceed their SLA deadline and notify the assigned admin';

    public function handle(FeatureFlagService $flags): int
    {
        if (! $flags->isEnabled('sla_deadlines_autolock', null)) {
            $this->warn('Feature flag "sla_deadlines_autolock" is disabled — skipping SLA check.');

            return self::SUCCESS;
        }

        $flagged = 0;
        $notified = 0;

        foreach ((array) config('fsb.sla', []) as $status => $hours) {
            $deadline = now()->subHours((int) $hours);

            $overdue = SourcingRequest::query()
                ->where('status', $status)
                ->where('is_restricted_due_to_delay', false)
                ->whereNotNull('status_changed_at')
                ->whereNotNull('assigned_to_admin_id')
                ->where('status_changed_at', '<', $deadline)
                ->get();

            foreach ($overdue as $request) {
                $request->update(['is_restricted_due_to_delay' => true]);
                $flagged++;

                $admin = $request->assignedAdmin;
                if ($admin) {
                    $admin->notify(new SlaDeadlineExceeded($request, (int) $hours));
                    $notified++;
                }
            }

            $this->info("Status '{$status}' (SLA {$hours}h): {$overdue->count()} request(s) restricted.");
        }

        $this->info("SLA check complete: {$flagged} flagged, {$notified} notification(s) sent.");

        return self::SUCCESS;
    }
}