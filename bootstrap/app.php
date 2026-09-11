<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('workflow:check-deadlines')->hourly();
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            $supportedLocales = ['eng', 'fr', 'ar'];

            $locale = $request->segment(1);
            $locale = $locale === 'en' ? 'eng' : $locale;

            if (!in_array($locale, $supportedLocales, true)) {
                $sessionLocale = session('locale');
                $sessionLocale = $sessionLocale === 'en' ? 'eng' : $sessionLocale;
                $locale = in_array($sessionLocale, $supportedLocales, true) ? $sessionLocale : null;
            }

            if (!$locale) {
                $locale = 'eng';
            }

            return route('login', ['locale' => $locale]);
        });

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'feature' => \App\Http\Middleware\CheckFeatureMiddleware::class,
            'verified.client' => \App\Http\Middleware\RequireVerifiedEmailForClients::class,
        ]);

        $middleware->web(prepend: [
            \App\Http\Middleware\AssignRequestId::class,
        ], append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\UpdateSessionActivity::class,
            \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->dontReport([
            AuthenticationException::class,
            \Illuminate\Auth\Access\AuthorizationException::class,
            \Illuminate\Validation\ValidationException::class,
            \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
            \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        ]);

        $exceptions->context(function () {
            try {
                $request = request();
            } catch (\Throwable) {
                return [];
            }

            if (! $request instanceof Request) {
                return [];
            }

            return array_filter([
                'user_id' => auth()->id(),
                'route' => $request->route()?->getName(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'request_id' => $request->attributes->get('request_id'),
                'ip' => $request->ip(),
            ], fn ($value) => $value !== null && $value !== '');
        });

        $exceptions->shouldRenderJsonWhen(function (Request $request, \Throwable $e) {
            return $request->expectsJson() || $request->hasHeader('X-Livewire');
        });

        $exceptions->renderable(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() !== 419) {
                return null;
            }

            if (! $e->getPrevious() instanceof TokenMismatchException) {
                return null;
            }

            $message = __('Your session has expired. Please refresh the page and try again.');
            $payload = [
                'message' => $message,
                'request_id' => $request->attributes->get('request_id'),
            ];

            if ($request->expectsJson() || $request->hasHeader('X-Livewire')) {
                return response()->json($payload, 419);
            }

            return redirect()
                ->back()
                ->with('error', $message)
                ->withInput();
        });

        $exceptions->respond(function (SymfonyResponse $response, \Throwable $e, Request $request) {
            $id = $request->attributes->get('request_id');
            if ($id) {
                $response->headers->set('X-Request-ID', $id);

                if ($response instanceof \Illuminate\Http\JsonResponse) {
                    $data = $response->getData(true);
                    if (is_array($data) && ! array_key_exists('request_id', $data)) {
                        $data['request_id'] = $id;
                        $response->setData($data);
                    }
                }
            }

            return $response;
        });
    })->create();
