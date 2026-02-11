<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureMiddleware
{
    public function __construct(protected \App\Services\FeatureFlagService $featureFlagService) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (! $this->featureFlagService->isEnabled($feature, $request->user())) {
            abort(404, __('Feature not available.'));
        }

        return $next($request);
    }
}
