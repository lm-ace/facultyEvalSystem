<?php

// Set the Laravel base path correctly
$laravelBase = __DIR__ . '/../MainFolder';

// Bootstrap storage paths to /tmp (writable on Vercel)
$_ENV['APP_STORAGE'] = '/tmp';

define('LARAVEL_START', microtime(true));

require $laravelBase . '/vendor/autoload.php';

$app = require_once $laravelBase . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
