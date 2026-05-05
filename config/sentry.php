<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sentry DSN
    |--------------------------------------------------------------------------
    |
    | This DSN is provided when creating a new project on Sentry
    |
    */
    'dsn' => env('SENTRY_LARAVEL_DSN'),

    /*
    |--------------------------------------------------------------------------
    | Release
    |--------------------------------------------------------------------------
    |
    | This value sets the release version that will be sent with events
    |
    */
    'release' => trim(explode('@', 'v1.0.0@' . (file_exists(base_path('VERSION')) ? trim(file_get_contents(base_path('VERSION')), "\n") : 'dev'))[0], 'v') ?? null,

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | This is the environment your application runs in
    |
    */
    'environment' => env('APP_ENV', 'development'),

    /*
    |--------------------------------------------------------------------------
    | Traces Sample Rate
    |--------------------------------------------------------------------------
    |
    | Set tracesSampleRate to 1.0 to capture 100% of transactions for
    | performance monitoring. We recommend adjusting this value in production.
    |
    */
    'traces_sample_rate' => (float) env('SENTRY_TRACES_SAMPLE_RATE', 0.1),

    /*
    |--------------------------------------------------------------------------
    | Profiles Sample Rate
    |--------------------------------------------------------------------------
    |
    | Set this to 1.0 to profile 100% of sampled transactions.
    | We recommend adjusting this value in production.
    |
    */
    'profiles_sample_rate' => (float) env('SENTRY_PROFILES_SAMPLE_RATE', 0.1),

    /*
    |--------------------------------------------------------------------------
    | Breadcrumbs
    |--------------------------------------------------------------------------
    |
    | Below you can define how Sentry logging behaves
    |
    */
    'breadcrumbs' => [
        // Capture Laravel logs in breadcrumbs
        'logs' => true,
        // Capture cache events in breadcrumbs
        'cache' => true,

        // Capture HTTP client requests in breadcrumbs
        'http_client_requests' => true,

        // Capture queue jobs in breadcrumbs
        'queue_jobs' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Attach Stack Traces
    |--------------------------------------------------------------------------
    |
    | Attach stack traces to all messages logged through the Monolog integration
    |
    */
    'attach_stacktrace' => true,

    /*
    |--------------------------------------------------------------------------
    | Max Value Length
    |--------------------------------------------------------------------------
    |
    | If the payload value is a string larger than this character limit,
    | the SDK will truncate the string before sending to Sentry
    |
    */
    'max_value_length' => 1024,

    /*
    |--------------------------------------------------------------------------
    | Max Breadcrumbs
    |--------------------------------------------------------------------------
    |
    | The number of breadcrumbs to keep in memory for later
    |
    */
    'max_breadcrumbs' => 100,

    /*
    |--------------------------------------------------------------------------
    | Integrations
    |--------------------------------------------------------------------------
    |
    | Set integrations to an empty array to disable all integrations at once.
    | To disable integrations, you can use the Integration facade as follows:
    |
    | 'integrations' => [
    |     \Sentry\Laravel\Integration::class,
    | ],
    |
    */
    'integrations' => null,

    /*
    |--------------------------------------------------------------------------
    | Ignored Exceptions
    |--------------------------------------------------------------------------
    |
    | These exceptions will not be reported to Sentry
    |
    */
    'ignore_exceptions' => [
        \Illuminate\Auth\AuthenticationException::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Capture Silenced Errors
    |--------------------------------------------------------------------------
    |
    | If true, the SDK will capture errors that are silenced with the
    | @ error control operator
    |
    */
    'capture_silenced_errors' => true,

    /*
    |--------------------------------------------------------------------------
    | Send Default PII
    |--------------------------------------------------------------------------
    |
    | If true, the SDK will capture PII (Personally Identifiable Information)
    |
    */
    'send_default_pii' => false,
];
