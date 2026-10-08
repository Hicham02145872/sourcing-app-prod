<?php

namespace App\Livewire\Admin;

use App\Models\SourcingOrder;
use App\Models\User;
use Livewire\Component;

final class AdminPerformanceAnalytics extends Component
{
    private const PHASES = [
        'pending_payment' => 'pending_payment',
        'paid' => 'paid',
        'shipment_preparing' => 'paid',
        'in_transit_china' => 'transit',
        'in_air_cargo' => 'transit',
        'arrival_uae' => 'transit',
        'customs_clearance_uae' => 'transit',
        'in_transit_uae' => 'transit',
        'arrival_destination_country' => 'delivered',
        'customs_clearance_destination_country' => 'delivered',
        'out_for_delivery' => 'delivered',
        'delivered' => 'delivered',
        'order_completed' => 'delivered',
        'delivery_failed' => 'issues',
        'shipment_delayed' => 'issues',
        'shipment_returned' => 'issues',
        'shipment_canceled' => 'issues',
        'waiting_for_refund' => 'refund',
        'refund_approved' => 'refund',
        'refunded' => 'refund',
        'refund_rejected' => 'refund',
    ];

    public array $metrics = [];

    public ?string $dateFrom = null;

    public ?string $dateTo = null;

    public function resetDates()
    {
        $this->dateFrom = null;
        $this->dateTo = null;
    }

    public function render()
    {
        $this->metrics = $this->buildMetrics();

        return view('livewire.admin.admin-performance-analytics', [
            'metrics' => $this->metrics,
        ]);
    }

    public function isOwnScope(): bool
    {
        $user = auth()->user();

        return $user !== null && ! $user->isSuperAdmin();
    }

    private function buildMetrics(): array
    {
        $adminNames = User::whereIn('role', ['admin', 'super_admin'])->pluck('name', 'id');

        $query = SourcingOrder::query();

        $user = auth()->user();
        if ($user !== null && ! $user->isSuperAdmin()) {
            $query->where('assigned_to_admin_id', $user->id);
        }

        if ($this->dateFrom && $this->dateTo) {
            $query->whereBetween('status_changed_at', [
                $this->dateFrom.' 00:00:00',
                $this->dateTo.' 23:59:59',
            ]);
        } elseif ($this->dateFrom) {
            $query->whereDate('status_changed_at', '>=', $this->dateFrom);
        } elseif ($this->dateTo) {
            $query->whereDate('status_changed_at', '<=', $this->dateTo);
        }

        $evidenceQuery = clone $query;

        $counts = $query
            ->select('assigned_to_admin_id', 'status')
            ->selectRaw('count(*) as total')
            ->groupBy('assigned_to_admin_id', 'status')
            ->get();

        $evidenceByKey = [];
        foreach ($evidenceQuery
            ->whereNotNull('tracking_number')
            ->where(function ($q) {
                $q->whereNotNull('parcel_photo_path')
                    ->orWhereNotNull('package_label_photo_path');
            })
            ->select('assigned_to_admin_id')
            ->selectRaw('count(*) as total')
            ->groupBy('assigned_to_admin_id')
            ->get() as $row) {
            $evidenceByKey[$row->assigned_to_admin_id ?: 0] = (int) $row->total;
        }

        $byAdmin = [];
        foreach ($counts as $row) {
            $adminKey = $row->assigned_to_admin_id ?: 0;
            $phase = self::PHASES[$row->status] ?? self::PHASES['order_completed'];

            if (! isset($byAdmin[$adminKey])) {
                $byAdmin[$adminKey] = [
                    'pending_payment' => 0,
                    'paid' => 0,
                    'transit' => 0,
                    'delivered' => 0,
                    'evidence_complete' => 0,
                    'issues' => 0,
                    'refund' => 0,
                    'total' => 0,
                ];
            }

            $byAdmin[$adminKey][$phase] += $row->total;
            $byAdmin[$adminKey]['total'] += $row->total;
        }

        $metrics = [];
        foreach ($byAdmin as $adminKey => $countsByPhase) {
            $metrics[] = [
                'admin_id' => $adminKey,
                'admin_name' => $adminKey !== 0
                    ? ($adminNames[$adminKey] ?? null)
                    : __('Unassigned'),
                ...$countsByPhase,
                'evidence_complete' => $evidenceByKey[$adminKey] ?? 0,
            ];
        }

        usort($metrics, fn ($a, $b) => $b['total'] <=> $a['total'] ?: $a['admin_name'] <=> $b['admin_name']);

        $user = auth()->user();
        if ($metrics === [] && $user !== null && ! $user->isSuperAdmin()) {
            $metrics[] = [
                'admin_id' => $user->id,
                'admin_name' => $user->name,
                'pending_payment' => 0,
                'paid' => 0,
                'transit' => 0,
                'delivered' => 0,
                'evidence_complete' => 0,
                'issues' => 0,
                'refund' => 0,
                'total' => 0,
            ];
        }

        return array_values($metrics);
    }
}
