<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if the authenticated user's role matches the required role
        if ($role === 'admin') {
            if (!$request->user()->isAdmin()) {
                abort(403, 'Unauthorized action.');
            }
        } elseif ($role === 'super_admin') {
            if (!$request->user()->isSuperAdmin()) {
                abort(403, 'Unauthorized action.');
            }
        } else {
            if ($request->user()->role !== $role) {
                // If not, abort with a 403 Forbidden response
                abort(403, 'Unauthorized action.');
            }
        }
        // If the role matches, proceed with the request
        return $next($request);
    }
}