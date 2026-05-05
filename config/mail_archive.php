<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mail archive (debug)
    |--------------------------------------------------------------------------
    |
    | Stores sent emails as JSON files in storage/app/mails for debugging.
    | Keep disabled in production unless storage permissions are correctly set.
    |
    */
    // Do not call app()->environment() in config bootstrap context.
    'enabled' => env('MAIL_ARCHIVE_ENABLED', env('APP_ENV') !== 'production'),
];

