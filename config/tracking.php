<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Python Executable Path
    |--------------------------------------------------------------------------
    |
    | The path to the Python executable used for running tracking scrapers.
    | Defaults to 'python' which assumes it's in the system PATH.
    |
    */

    'python_path' => env('TRACKING_PYTHON_PATH', 'python'),

    /*
    |--------------------------------------------------------------------------
    | Chrome Binary Path
    |--------------------------------------------------------------------------
    |
    | The path to the Chrome/Chromium binary for Selenium scrapers.
    | Required for headless browser automation.
    |
    */

    'chrome_binary_path' => env('CHROME_BINARY_PATH'),

    /*
    |--------------------------------------------------------------------------
    | ChromeDriver Path
    |--------------------------------------------------------------------------
    |
    | The path to the ChromeDriver executable for Selenium scrapers.
    | If not set, webdriver-manager will attempt to download it automatically.
    |
    */

    'chromedriver_path' => env('CHROMEDRIVER_PATH'),

    /*
    |--------------------------------------------------------------------------
    | Cache TTL (Time To Live)
    |--------------------------------------------------------------------------
    |
    | How long (in minutes) to cache tracking results before fetching fresh data.
    | Default: 30 minutes
    |
    */

    'cache_ttl' => env('TRACKING_CACHE_TTL', 30),

    /*
    |--------------------------------------------------------------------------
    | Cache TTL by Status (Minutes)
    |--------------------------------------------------------------------------
    |
    | Permet d'ajuster le TTL en fonction du statut fonctionnel.
    | Ces valeurs surchargent "cache_ttl" si un mapping est trouvé.
    |
    */

    'cache_ttl_by_status' => [
        // Colis livrés: on peut garder longtemps en cache
        'delivered' => env('TRACKING_CACHE_TTL_DELIVERED', 60 * 24), // 24h
        // En transit: on veut des données plus fraîches
        'in_transit' => env('TRACKING_CACHE_TTL_IN_TRANSIT', 30),    // 30 min
        // Pending / erreur / non trouvé: TTL très court
        'pending' => env('TRACKING_CACHE_TTL_PENDING', 5),
        'error' => env('TRACKING_CACHE_TTL_ERROR', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Maximum number of tracking requests allowed per minute per user.
    | Default: 10 requests per minute
    |
    */

    'rate_limit' => env('TRACKING_RATE_LIMIT', 10),

    /*
    |--------------------------------------------------------------------------
    | Selenium Concurrency Limits
    |--------------------------------------------------------------------------
    |
    | Limite globale et par provider pour les jobs Selenium (scrapers).
    | Ces compteurs sont gérés via le cache dans RunSeleniumTrackingJob.
    |
    */

    'selenium_max_concurrent_global' => env('TRACKING_SELENIUM_MAX_CONCURRENT_GLOBAL', 1),

    'selenium_max_concurrent_per_provider' => env('TRACKING_SELENIUM_MAX_CONCURRENT_PER_PROVIDER', 1),

    /*
    |--------------------------------------------------------------------------
    | Circuit Breaker (Failure Memory)
    |--------------------------------------------------------------------------
    |
    | Si un numéro échoue plusieurs fois de suite, on peut le mettre
    | temporairement en "blocage" pour éviter de spammer les providers.
    |
    */

    'circuit_breaker' => [
        // Nombre d'échecs consécutifs avant blocage
        'failure_threshold' => env('TRACKING_CB_FAILURE_THRESHOLD', 3),
        // Durée du blocage en minutes
        'cooldown_minutes' => env('TRACKING_CB_COOLDOWN_MINUTES', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | SSL Verification
    |--------------------------------------------------------------------------
    |
    | Whether to verify SSL certificates when making API requests.
    | Set to false if you encounter "local issuer certificate" issues.
    | Default: true
    |
    | WARNING: Disabling SSL verification is not recommended for production.
    |
    */

    'verify_ssl' => env('TRACKING_VERIFY_SSL', true),

    /*
    |--------------------------------------------------------------------------
    | Proxy Configuration
    |--------------------------------------------------------------------------
    |
    | Configure proxy settings for outbound requests.
    | Useful for avoiding IP bans or accessing geo-restricted content.
    |
    */

    'proxy' => [
        'enabled' => env('TRACKING_PROXY_ENABLED', false),
        'http' => env('TRACKING_HTTP_PROXY'),
        'https' => env('TRACKING_HTTPS_PROXY'),
        'no_proxy' => env('TRACKING_NO_PROXY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Sync providers for cron (auto-update statuses)
    |--------------------------------------------------------------------------
    |
    | Providers for which the cron may call refreshTracking() when cache is
    | empty (API-based, fast). Selenium providers (ITDIDA, ChoiceXP, UPS)
    | are not listed so the cron does not block; they use cache only.
    |
    */
    'sync_providers_for_cron' => ['Faster', 'FSB'],

    /*
    |--------------------------------------------------------------------------
    | Carrier labels (for admin dropdown when shipping company has child carriers)
    |--------------------------------------------------------------------------
    | GCC & Faster = same transporteur. FSB is not a transporteur (internal ref).
    */
    'carrier_labels' => [
        'gcc' => 'GCC / Faster',
        'ups' => 'UPS',
        'itdida' => 'Itdida',
        'choicexp' => 'Choice XP',
    ],

    /*
    |--------------------------------------------------------------------------
    | Selenium HTTP Server (P1.1 — persistent Chrome)
    |--------------------------------------------------------------------------
    |
    | When enabled, AbstractSeleniumTrackingService calls the local Flask server
    | (scraper/tracking_server.py) instead of spawning a new Python process per
    | request.  Set TRACKING_SELENIUM_SERVER_ENABLED=true on the VPS after the
    | server is running and healthy (curl http://127.0.0.1:5001/health).
    |
    | If the server is unreachable a ConnectionException is caught and the
    | request falls back automatically to the legacy process-based mode.
    |
    */
    'selenium_server' => [
        'enabled'  => env('TRACKING_SELENIUM_SERVER_ENABLED', false),
        'base_url' => env('TRACKING_SELENIUM_SERVER_URL', 'http://127.0.0.1:5001'),
        'timeout'  => (int) env('TRACKING_SELENIUM_SERVER_TIMEOUT', 30),
    ],

];
