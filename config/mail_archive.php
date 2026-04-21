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
    'enabled' => env('MAIL_ARCHIVE_ENABLED', app()->environment(['local', 'testing'])),
];

