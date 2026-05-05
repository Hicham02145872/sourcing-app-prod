<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::create(
        '/notifications',
        'GET'
    )
);
$request->headers->set('Accept', 'application/json');
Auth::loginUsingId(4); // Try user 4 this time (maybe it has the updates)
$controller = app(\App\Http\Controllers\NotificationController::class);
file_put_contents('notif_out.json', json_encode(json_decode($controller->index($request)->getContent()), JSON_PRETTY_PRINT));
