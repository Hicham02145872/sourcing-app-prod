<?php

namespace App\Livewire\Admin;

use App\Models\RequestStatusLog;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Component;

class AdminPerformanceAnalytics extends Component
{
    public string $startDate = '';
    public string $endDate = '';

    public array $metrics = [];

    public function applyDateRange(): void
    {
        // Properties are already bound to the request; re-render applies the filter.
    }

    public function resetDateRange(): void
    {
        $this->reset('startDate', 'endDate');
    }

    public function render()
    {
        $query = RequestStatusLog::query();

        if ($this->startDate) {
            $query->where('changed_at', '>=', Carbon::parse($this->startDate)->startOfDay());
        }
        if ($this->endDate) {
            $query->where('changed_at', '<=', Carbon::parse($this->endDate)->endOfDay());
        }

        $this->metrics = $this->buildMetrics($query->get());

        return view('livewire.admin.admin-performance-analytics', [
            'metrics' => $this->metrics,
        ]);
    }

    private function buildMetrics($logs): array
    {
        $admins = User::whereIn('role', ['admin', 'super_admin'])->pluck('name', 'id');

        $metrics = [];

        foreach ($logs->groupBy('changed_by_user_id') as $adminId => $adminLogs) {
            $adminLogs = $adminLogs->sortBy('changed_at')->values();

            $reviews = $adminLogs->where('to_status', 'in_review')->count();
            $responses = $adminLogs->where('to_status', 'quoted')->count();
            $acceptances = $adminLogs->where('to_status', 'accepted')->count();

            $pairDurations = [];
            foreach ($adminLogs->groupBy('sourcing_request_id') as $requestLogs) {
                $requestLogs = $requestLogs->sortBy('changed_at')->values();
                $inReviewTimes = $requestLogs->where('to_status', 'in_review')->pluck('changed_at');
                $quotedTimes = $requestLogs->where('to_status', 'quoted')->pluck('changed_at');

                foreach ($inReviewTimes as $inReviewAt) {
                    foreach ($quotedTimes as $quotedAt) {
                        if ($quotedAt->gt($inReviewAt)) {
                            $pairDurations[] = $quotedAt->diffInHours($inReviewAt);
                            break;
                        }
                    }
                }
            }

            $metrics[] = [
                'admin_id' => $adminId,
                'admin_name' => $admins[$adminId] ?? null,
                'reviews' => $reviews,
                'responses' => $responses,
                'acceptances' => $acceptances,
                'acceptance_rate' => $responses > 0 ? round(($acceptances / $responses) * 100, 1) : null,
                'avg_response_hours' => count($pairDurations) > 0 ? round(array_sum($pairDurations) / count($pairDurations), 2) : null,
            ];
        }

        usort($metrics, fn ($a, $b) => $b['reviews'] <=> $a['reviews']);

        return $metrics;
    }
}