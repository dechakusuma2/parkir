<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Memuat skrip pemeliharaan jika aplikasi sedang berada dalam mode maintenance.
// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Memuat autoloader Composer untuk mengaktifkan kelas aplikasi dan dependensi.
// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Menyiapkan aplikasi Laravel dan memproses permintaan HTTP yang masuk.
// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
