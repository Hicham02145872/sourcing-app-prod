<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireVerifiedEmailForClients
{
    /**
     * Handle an incoming request.
     *
     * Require email verification only for clients, not for admins or super_admins.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Admins and super_admins don't need email verification
        if ($user && ($user->isAdmin() || $user->isSuperAdmin())) {
            return $next($request);
        }

        // Clients must have verified email
        if ($user && $user->isClient() && !$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
