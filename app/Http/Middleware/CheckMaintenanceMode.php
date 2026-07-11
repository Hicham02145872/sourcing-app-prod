<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Setting::isMaintenanceMode()) {
            return $next($request);
        }

        $user = $request->user();

        if ($user && ($user->isDeveloper() || $user->isAdmin())) {
            return $next($request);
        }

        if ($request->is('up') || $request->is('health') || $request->is('api/*') || $request->is('*/dev/login')) {
            return $next($request);
        }

        $message = Setting::getMaintenanceMessage();

        return response()->view('maintenance', [
            'message' => $message,
        ], 503);
    }
}
