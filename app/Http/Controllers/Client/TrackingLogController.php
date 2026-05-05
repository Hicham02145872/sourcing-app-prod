<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\TrackingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingLogController extends Controller
{
    public function index(string $locale)
    {
        $logs = TrackingLog::where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('client.tracking.logs', compact('logs'));
    }
}
