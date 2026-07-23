<?php

// 1. Create writable storage directories in Vercel's /tmp environment
$directories = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}

// 2. Prepare SQLite database in /tmp
$tmpSqlite = '/tmp/database.sqlite';
if (!file_exists($tmpSqlite)) {
    $srcSqlite = __DIR__ . '/../database/database.sqlite';
    if (file_exists($srcSqlite)) {
        copy($srcSqlite, $tmpSqlite);
    } else {
        touch($tmpSqlite);
    }
}

// 3. Populate $_ENV, $_SERVER, and putenv so Laravel env() helper reads them correctly
$envVars = [
    'APP_KEY' => 'base64:PGVvE56B808tV7UnQZa09mOpn8L9TN+1HjXT2b8g5Xk=',
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'true',
    'LOG_CHANNEL' => 'stderr',
    'SESSION_DRIVER' => 'cookie',
    'DB_CONNECTION' => 'sqlite',
    'DB_DATABASE' => $tmpSqlite,
    'VIEW_COMPILED_PATH' => '/tmp/storage/framework/views',
    'APP_CONFIG_CACHE' => '/tmp/bootstrap/cache/config.php',
    'APP_SERVICES_CACHE' => '/tmp/bootstrap/cache/services.php',
    'APP_PACKAGES_CACHE' => '/tmp/bootstrap/cache/packages.php',
    'APP_ROUTES_CACHE' => '/tmp/bootstrap/cache/routes.php',
    'APP_EVENTS_CACHE' => '/tmp/bootstrap/cache/events.php',
];

foreach ($envVars as $key => $val) {
    putenv("{$key}={$val}");
    $_ENV[$key] = $val;
    $_SERVER[$key] = $val;
}

// Forward requests to Laravel's public entrypoint
require __DIR__ . '/../public/index.php';
