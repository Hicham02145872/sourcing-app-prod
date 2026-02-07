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

];
