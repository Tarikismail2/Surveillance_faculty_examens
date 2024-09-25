<?php

use Illuminate\Http\Request;

// Set the maximum execution time to 10 minutes
ini_set('max_execution_time', 600);

// Check if the application is in maintenance mode
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Autoload classes and bootstrap the application
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Handle the incoming request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Request::capture()
);

// Send the response to the browser
$response->send();

// Terminate the kernel
$kernel->terminate($request, $response);
