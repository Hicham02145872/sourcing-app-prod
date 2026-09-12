<?php

namespace App\Console\Commands;

use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\User;
use App\Notifications\SlaDeadlineExceeded;
use App\Notifications\SourcingOrderDeadlineExceeded;
use App\Services\FeatureFlagService;
use Illuminate\Console\Command;

class CheckWorkflowDeadlines extends Command
{
    protected $signature = 'workflow:check-deadlines';

    protected $description = 'Flag requests and orders that exceed their action deadline and notify the assigned admin';

    public function handle(FeatureFlagService $flags): int
    {
        if (! $flags->isEnabled('sla_deadlines_autolock', null)) {
            $this->warn('Feature flag "sla_deadlines_autolock" is disabled — skipping deadline check.');

            return self::SUCCESS;
        }

        $flagged = 0;
        $notified = 0;

        $rules = (array) config('fsb.sla', []);

        foreach ((array) ($rules['requests'] ?? []) as $status => $hours) {
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

            $this->info("Request status '{$status}' (deadline {$hours}h): {$overdue->count()} request(s) flagged.");
        }

        foreach ((array) ($rules['orders'] ?? []) as $status => $hours) {
            $deadline = now()->subHours((int) $hours);
            $escalate = $status === 'paid';

            $overdue = SourcingOrder::query()
                ->where('status', $status)
                ->where('is_restricted_due_to_delay', false)
                ->whereNotNull('status_changed_at')
                ->whereNotNull('assigned_to_admin_id')
                ->where('status_changed_at', '<', $deadline)
                ->get();

            foreach ($overdue as $order) {
                $order->update(['is_restricted_due_to_delay' => true]);
                $flagged++;

                $admin = $order->assignedAdmin;
                if ($admin) {
                    $admin->notify(new SourcingOrderDeadlineExceeded($order, (int) $hours));
                    $notified++;
                }

                if ($escalate) {
                    foreach (User::where('role', 'super_admin')->get() as $superAdmin) {
                        $superAdmin->notify(new SourcingOrderDeadlineExceeded($order, (int) $hours, true));
                        $notified++;
                    }
                }
            }

            $this->info("Order status '{$status}' (deadline {$hours}h): {$overdue->count()} order(s) flagged.");
        }

        $this->info("Deadline check complete: {$flagged} flagged, {$notified} notification(s) sent.");

        return self::SUCCESS;
    }
}
