<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrackingLog;
use Illuminate\Http\Request;

class TrackingLogController extends Controller
{
    public function index()
    {
        $logs = TrackingLog::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.tracking-logs.index', compact('logs'));
    }

    public function show(TrackingLog $log)
    {
        return response()->json($log);
    }
}
