<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AppliesDateRangeFilter;
use App\Http\Controllers\Controller;
use App\Models\TrackingLog;
use Illuminate\Http\Request;

class TrackingLogController extends Controller
{
    use AppliesDateRangeFilter;

    public function index(Request $request)
    {
        $logs = TrackingLog::with('user')
            ->latest();

        // Filter by creation date range (start / end)
        $this->applyDateRangeFilter($logs, $request->query('date_debut'), $request->query('date_fin'));

        $logs = $logs->paginate(20)->withQueryString();

        return view('admin.tracking-logs.index', compact('logs'));
    }

    public function show(TrackingLog $log)
    {
        return response()->json($log);
    }
}
