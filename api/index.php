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

// 3. Fallback environment settings for Vercel Serverless
putenv('APP_KEY=base64:PGVvE56B808tV7UnQZa09mOpn8L9TN+1HjXT2b8g5Xk=');
putenv('APP_ENV=production');
putenv('APP_DEBUG=true');

// Fix Read-Only filesystem logging & session errors
putenv('LOG_CHANNEL=stderr');
putenv('SESSION_DRIVER=cookie');
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=' . $tmpSqlite);

// Storage path overrides for Vercel
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes.php');
putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');

// Forward requests to Laravel's public entrypoint
require __DIR__ . '/../public/index.php';
