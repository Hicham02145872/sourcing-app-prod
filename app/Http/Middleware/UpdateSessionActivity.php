<?php

namespace App\Http\Middleware;

use App\Models\UserSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateSessionActivity
{
    /**
     * Handle an incoming request.
     *
     * Update the last_activity timestamp for the current session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user() && session()->getId()) {
            UserSession::where('session_id', session()->getId())
                ->where('user_id', $request->user()->id)
                ->update(['last_activity' => now()]);
        }

        return $response;
    }
}
