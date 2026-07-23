<?php

// Create writable storage directories in Vercel's /tmp environment
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

// Default environment fallback settings for Vercel deployment
if (!getenv('APP_KEY')) {
    putenv('APP_KEY=base64:PGVvE56B808tV7UnQZa09mOpn8L9TN+1HjXT2b8g5Xk=');
}
if (!getenv('APP_ENV')) {
    putenv('APP_ENV=production');
}
if (!getenv('APP_DEBUG')) {
    putenv('APP_DEBUG=true');
}

// Set environment variables for storage paths in Vercel Serverless
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes.php');
putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');

// Forward requests to Laravel's public entrypoint
require __DIR__ . '/../public/index.php';
