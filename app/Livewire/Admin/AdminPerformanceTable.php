<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AdminPerformanceTable extends Component
{
    use WithPagination;

    public function render()
    {
        $adminPerformance = User::where('role', 'admin')
            ->withCount([
                'assignedSourcingRequests',
                'assignedSourcingOrders',
            ])
            ->withSum('assignedSourcingOrders as total_net_profit', 'net_profit_or_loss')
            ->orderBy('assigned_sourcing_requests_count', 'desc')
            ->paginate(10);

        return view('livewire.admin.admin-performance-table', [
            'adminPerformance' => $adminPerformance,
        ]);
    }
}
