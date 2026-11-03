<?php

// Prevent any deprecations or warnings from outputting before HTTP headers
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// 1. Prepare writable storage directories in /tmp for Vercel Serverless environment
$dirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/storage/app/public',
];

foreach ($dirs as $dir) {
    if (! is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 2. Copy pre-seeded SQLite database to /tmp if it doesn't exist
$sourceDb = __DIR__ . '/../database/database.sqlite';
$targetDb = '/tmp/database.sqlite';

if (! file_exists($targetDb) && file_exists($sourceDb)) {
    copy($sourceDb, $targetDb);
}

// 3. Register the Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// 4. Bootstrap Laravel application
/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 5. Dynamically configure storage path to /tmp/storage
$app->useStoragePath('/tmp/storage');

// 6. Handle the incoming HTTP request
$app->handleRequest(\Illuminate\Http\Request::capture());
